import { ethers } from "ethers";

export const CONTRACT_ADDRESS = "YOUR_CONTRACT_ADDRESS";
export const FARBITS_TOKEN_ADDRESS = "0xef5c3c8d94cd87c6a6c8fc1cfb1d4583e4c3cb46";

export const ABI = [
    "function createMatch(uint256 stake)",
    "function joinMatch(uint256 matchId)",
    "function resolveMatch(uint256 matchId, address winner)",
    "function refundMatch(uint256 matchId)",
    "function forceRefundActiveMatch(uint256 matchId)",
    "function matches(uint256) view returns (address playerOne, address playerTwo, uint256 stake, uint8 status, address winner, uint256 createdAt)"
];

export async function getContract() {
    if (!window.ethereum) {
        alert("Install MetaMask");
        return;
    }

    const provider = new ethers.BrowserProvider(window.ethereum);
    const signer = await provider.getSigner();

    return new ethers.Contract(CONTRACT_ADDRESS, ABI, signer);
}