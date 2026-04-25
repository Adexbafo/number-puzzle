// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";

contract MockFarbits is ERC20 {
    constructor() ERC20("Mock Farbits", "MFARB") {
        // Mint 1 million tokens to the deployer
        _mint(msg.sender, 1000000 * 10**decimals());
    }

    // Function to let anyone mint tokens for testing
    function mint(address to, uint256 amount) public {
        _mint(to, amount);
    }
}
