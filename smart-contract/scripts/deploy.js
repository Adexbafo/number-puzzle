const hre = require("hardhat");

async function main() {
  const [deployer] = await hre.ethers.getSigners();

  console.log("Deploying contracts with the account:", deployer.address);

  // Farbits Token Address on Base
  const FARBITS_TOKEN = "0xef5c3c8d94cd87c6a6c8fc1cfb1d4583e4c3cb46";
  
  // Treasury Address (Where fees go). 
  // IMPORTANT: Replace this with your actual treasury address or use the deployer for now.
  const TREASURY = deployer.address; 

  console.log("Deploying NumberPuzzle with:");
  console.log(" - Token:", FARBITS_TOKEN);
  console.log(" - Treasury:", TREASURY);

  const NumberPuzzle = await hre.ethers.getContractFactory("NumberPuzzle");
  const contract = await NumberPuzzle.deploy(FARBITS_TOKEN, TREASURY);

  await contract.waitForDeployment();

  const address = await contract.getAddress();
  console.log("NumberPuzzle deployed to:", address);

  console.log("\nDeployment complete! Copy the address above to resources/js/web3.js");
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(error);
    process.exit(1);
  });
