import React from "react";
import ReactDOM from "react-dom";
import "./index.css";
import reportWebVitals from "./reportWebVitals";
import { Wallet } from "./components/Wallet/Wallet.tsx";


if (document.querySelectorAll('.solpress-login')[0]) {
  document.querySelectorAll('.solpress-login').forEach(element => {
    ReactDOM.createRoot(element).render(
      <React.StrictMode>
        <Wallet id={element.id} />
      </React.StrictMode>
    );
  })
}


// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
