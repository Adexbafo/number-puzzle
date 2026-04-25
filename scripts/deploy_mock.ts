import { ethers } from "hardhat";

async function main() {
  const [deployer] = await ethers.getSigners();

  console.log("Deploying MockFarbits with the account:", deployer.address);

  const MockFarbits = await ethers.getContractFactory("MockFarbits");
  const token = await MockFarbits.deploy();

  await token.waitForDeployment();

  const address = await token.getAddress();
  console.log("MockFarbits deployed to:", address);
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(error);
    process.exit(1);
  });
