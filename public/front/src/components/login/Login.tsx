//import { WalletNotConnectedError } from '@solana/wallet-adapter-base';
import { useWallet } from "@solana/wallet-adapter-react";
import React, { FC, useCallback, useState } from "react";
import SolanaAPI from "../../api/SolanaAPI.ts";
import bs58 from "bs58";
import { sign } from "tweetnacl";
import "./style.css";
import ErrorMessage from "../ErrorMessage/ErrorMessage";

export const Login: FC = ({ id }) => {
  //const { connection } = useConnection();
  const { publicKey, signMessage } = useWallet();
  console.log("Section Id", id);
  const parentSection = document.getElementById(id);
  const buttonText = parentSection.dataset.buttonText;
  const sourcePage = parentSection.dataset.sourcePage;
  const signInMessage = parentSection.dataset.message;
  const [errMsg, setErrMsg] = useState("");

  //Get Redirection URL Function
  function getRedirectionURL() {
    if (
      document.querySelector("form[name='loginform'] input[name='redirect_to']")
    ) {
      const redirectionInput = document.querySelector(
        "form[name='loginform'] input[name='redirect_to']"
      ) as HTMLInputElement;
      const redirectionURL = redirectionInput.value;
      console.log("Redirection URL", redirectionURL ? redirectionURL : "");
      return redirectionURL;
    } else {
      return "";
    }
  }
  const redirectionURL = getRedirectionURL();

  console.log("Button Text: ", buttonText);
  console.log("Source Page: ", sourcePage);
  console.log("Sign In Message:", signInMessage);

  const onClick = useCallback(async () => {
    try {
      // `publicKey` will be null if the wallet isn't connected
      if (!publicKey) {
        setErrMsg("Wallet not connected!");
        throw new Error("Wallet not connected!");
      }

      // `signMessage` will be undefined if the wallet doesn't support it
      if (!signMessage) {
        setErrMsg("Wallet does not support message signing!");
        throw new Error("Wallet does not support message signing!");
      }

      // Encode anything as bytes
      const message = new TextEncoder().encode(signInMessage);

      // Sign the bytes using the wallet

      const signature = await signMessage(message);
      console.log("Signature:", signature);
      // Verify that the bytes were signed using the private key that matches the known public key
      if (!sign.detached.verify(message, signature, publicKey.toBytes())) {
        setErrMsg("Invalid signature!");
        throw new Error("Invalid signature!");
      }
      //alert(`Message signature: ${bs58.encode(signature)}`);
      console.log("Publick key: ", publicKey?.toBase58());
      console.log("Message signature: ", bs58.encode(signature));

      // await SolanaAPI.loginWithPublicKey(
      //   publicKey?.toBase58(),
      //   sourcePage,
      //   redirectionURL,
      //   signInMessage,
      //   bs58.encode(signature)
      // );
      await SolanaAPI.loginWithPublicKey(
        publicKey?.toBase58(),
        sourcePage,
        redirectionURL,
        signInMessage,
        bs58.encode(signature)
      );
    } catch (error: any) {
      //alert(`Signing failed: ${error?.message}`);
      //setErrMsg(error);
      setErrMsg(error);
      console.log(error);
    }
    console.log(errMsg);
  }, [publicKey, signMessage]);

  return (
    <>
      <input
        type="button"
        value={buttonText}
        onClick={onClick}
        disabled={!publicKey}
        className="wallet-adapter-button wallet-adapter-button-trigger"
      />
      {errMsg ? <ErrorMessage message={errMsg} /> : ""}
    </>
  );
};

