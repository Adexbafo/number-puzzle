import { ethers } from "hardhat";

async function main() {
  const [deployer] = await ethers.getSigners();

  console.log("Deploying contracts with the account:", deployer.address);

  const FARBITS_TOKEN = "0xef5c3c8d94cd87c6a6c8fc1cfb1d4583e4c3cb46";
  const TREASURY = deployer.address; 

  console.log("Deploying NumberPuzzle with:");
  console.log(" - Token:", FARBITS_TOKEN);
  console.log(" - Treasury:", TREASURY);

  const NumberPuzzle = await ethers.getContractFactory("NumberPuzzle");
  const contract = await NumberPuzzle.deploy(FARBITS_TOKEN, TREASURY);

  await contract.waitForDeployment();

  const address = await contract.getAddress();
  console.log("NumberPuzzle deployed to:", address);
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(error);
    process.exit(1);
  });
