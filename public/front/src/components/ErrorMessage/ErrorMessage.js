import "./style.css";
import React from "react";

export default function ErrorMessage({ message }) {
  return (
    <div className="alert error-alert">
      {message}
    </div>
  );
}