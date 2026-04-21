// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC20/utils/SafeERC20.sol";
import "@openzeppelin/contracts/utils/ReentrancyGuard.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

/**
 * @title NumberPuzzle
 * @dev Peer-to-peer wagering contract for the Number Puzzle game.
 * Uses Farbits ($FARB) token for stakes.
 */
contract NumberPuzzle is ReentrancyGuard, Ownable {
    using SafeERC20 for IERC20;

    IERC20 public immutable token;

    uint256 public feePercent = 5; // max 10%
    address public treasury;

    uint256 public matchCounter;
    uint256 public constant MATCH_TIMEOUT = 10 minutes;
    uint256 public constant ACTIVE_MATCH_TIMEOUT = 1 days;

    enum Status {
        Waiting,
        Active,
        Finished,
        Refunded
    }

    struct Match {
        address playerOne;
        address playerTwo;
        uint256 stake;
        Status status;
        address winner;
        uint256 createdAt;
    }

    mapping(uint256 => Match) public matches;

    // -------- Errors (gas efficient) --------
    error InvalidStake();
    error MatchNotFound();
    error AlreadyJoined();
    error MatchNotActive();
    error AlreadyFinished();
    error NotStarted();
    error InvalidWinner();
    error TimeoutNotReached();

    // -------- Events --------
    event MatchCreated(uint256 indexed id, address indexed player, uint256 stake);
    event MatchJoined(uint256 indexed id, address indexed player);
    event MatchResolved(uint256 indexed id, address winner, uint256 payout, uint256 fee);
    event MatchRefunded(uint256 indexed id);

    constructor(address _token, address _treasury) Ownable(msg.sender) {
        require(_token != address(0), "Invalid token");
        require(_treasury != address(0), "Invalid treasury");

        token = IERC20(_token);
        treasury = _treasury;
    }

    // -------- CREATE --------
    function createMatch(uint256 _stake) external nonReentrant {
        if (_stake == 0) revert InvalidStake();

        token.safeTransferFrom(msg.sender, address(this), _stake);

        matches[matchCounter] = Match({
            playerOne: msg.sender,
            playerTwo: address(0),
            stake: _stake,
            status: Status.Waiting,
            winner: address(0),
            createdAt: block.timestamp
        });

        emit MatchCreated(matchCounter, msg.sender, _stake);

        matchCounter++;
    }

    // -------- JOIN --------
    function joinMatch(uint256 _id) external nonReentrant {
        Match storage m = matches[_id];

        if (m.playerOne == address(0)) revert MatchNotFound();
        if (m.playerTwo != address(0)) revert AlreadyJoined();
        if (m.status != Status.Waiting) revert MatchNotActive();

        token.safeTransferFrom(msg.sender, address(this), m.stake);

        m.playerTwo = msg.sender;
        m.status = Status.Active;
        // Reset createdAt to join time for active timeout tracking
        m.createdAt = block.timestamp; 

        emit MatchJoined(_id, msg.sender);
    }

    // -------- RESOLVE --------
    function resolveMatch(uint256 _id, address _winner)
        external
        onlyOwner
        nonReentrant
    {
        Match storage m = matches[_id];

        if (m.status != Status.Active) revert MatchNotActive();
        if (m.playerTwo == address(0)) revert NotStarted();

        // winner must be player1, player2, or zero (draw)
        if (
            _winner != address(0) &&
            _winner != m.playerOne &&
            _winner != m.playerTwo
        ) revert InvalidWinner();

        uint256 total = m.stake * 2;
        uint256 fee = (total * feePercent) / 100;
        uint256 payout = total - fee;

        m.status = Status.Finished;
        m.winner = _winner;

        // fee → treasury
        if (fee > 0) {
            token.safeTransfer(treasury, fee);
        }

        if (_winner == address(0)) {
            // draw
            token.safeTransfer(m.playerOne, m.stake);
            token.safeTransfer(m.playerTwo, m.stake);
        } else {
            token.safeTransfer(_winner, payout);
        }

        emit MatchResolved(_id, _winner, payout, fee);
    }

    // -------- REFUND (Waiting) --------
    function refundMatch(uint256 _id) external nonReentrant {
        Match storage m = matches[_id];

        if (m.status != Status.Waiting) revert AlreadyFinished();
        if (block.timestamp < m.createdAt + MATCH_TIMEOUT)
            revert TimeoutNotReached();

        m.status = Status.Refunded;

        token.safeTransfer(m.playerOne, m.stake);

        emit MatchRefunded(_id);
    }

    /**
     * @dev Allows players to recover funds if an active match is never resolved by the owner.
     * Can be called by anyone after 1 day of inactivity.
     */
    function forceRefundActiveMatch(uint256 _id) external nonReentrant {
        Match storage m = matches[_id];
        if (m.status != Status.Active) revert MatchNotActive();
        if (block.timestamp < m.createdAt + ACTIVE_MATCH_TIMEOUT)
            revert TimeoutNotReached();

        m.status = Status.Refunded;
        
        token.safeTransfer(m.playerOne, m.stake);
        token.safeTransfer(m.playerTwo, m.stake);

        emit MatchRefunded(_id);
    }

    // -------- ADMIN --------
    function setFee(uint256 _fee) external onlyOwner {
        require(_fee <= 10, "Max 10%");
        feePercent = _fee;
    }

    function setTreasury(address _treasury) external onlyOwner {
        require(_treasury != address(0), "Invalid");
        treasury = _treasury;
    }
}
