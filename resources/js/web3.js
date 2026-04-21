import { ethers } from "ethers";

export const CONTRACT_ADDRESS = "YOUR_CONTRACT_ADDRESS";
export const FARBITS_TOKEN_ADDRESS = "0xef5c3c8d94cd87c6a6c8fc1cfb1d4583e4c3cb46";

export const ABI = [
    "function createMatch(uint256 stake)",
    "function joinMatch(uint256 matchId)",
    "function resolveMatch(uint256 matchId, address winner)",
    "function refundMatch(uint256 matchId)",
    "function forceRefundActiveMatch(uint256 matchId)",
    "function treasury() view returns (address)",
    "function matches(uint256) view returns (address playerOne, address playerTwo, uint256 stake, uint8 status, address winner, uint256 createdAt)"
];

export const ERC20_ABI = [
    "function balanceOf(address owner) view returns (uint256)",
    "function allowance(address owner, address spender) view returns (uint256)",
    "function approve(address spender, uint256 amount) returns (bool)"
];

export async function getContract() {
    if (!window.ethereum) return null;
    const provider = new ethers.BrowserProvider(window.ethereum);
    const signer = await provider.getSigner();
    return new ethers.Contract(CONTRACT_ADDRESS, ABI, signer);
}

/**
 * Fetches the current reward pool balance (Treasury balance of $FARB)
 */
export async function getRewardPoolBalance() {
    if (!window.ethereum || CONTRACT_ADDRESS === "YOUR_CONTRACT_ADDRESS") {
        return "10,000"; // Default placeholder
    }
    
    try {
        const provider = new ethers.BrowserProvider(window.ethereum);
        const contract = new ethers.Contract(CONTRACT_ADDRESS, ABI, provider);
        const treasuryAddress = await contract.treasury();
        
        const tokenContract = new ethers.Contract(FARBITS_TOKEN_ADDRESS, ERC20_ABI, provider);
        const balance = await tokenContract.balanceOf(treasuryAddress);
        
        return ethers.formatUnits(balance, 18);
    } catch (error) {
        console.error("Error fetching reward pool:", error);
        return "0";
    }
}