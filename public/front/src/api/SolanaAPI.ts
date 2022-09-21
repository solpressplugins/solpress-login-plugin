declare const window: any;

const $: any = window.jQuery;

class SolanaAPI {
  async loginWithPublicKey(
    publicKey: any,
    sourcePage: any,
    redirectionURL: any,
    signInMessage: any,
    signature: any
  ): Promise<number> {
    const url = window.solpress_wordpress_vars.ajax_url;
    const security = window.solpress_wordpress_vars.security;
    const action = window.solpress_wordpress_vars.action_login_button;
    const data = {
      action,
      security,
      publicKey,
      sourcePage,
      redirectionURL,
      signInMessage,
      signature,
    };

    return new Promise((resolve, reject) => {
      $.ajax({
        url,
        type: "POST",
        data,
        success: (res: any) => {
          if (res) {
            resolve(res);
            console.log(res)
            console.log(res?.success);
            if(res?.data?.redirectUrl){
            window.location.replace(res?.data?.redirectUrl);
            }
          } else {
            reject("Failed to login");
          }
        },
        error: (err: any) => {
          console.log(err?.responseJSON?.data?.errorMessage);
          reject(err?.responseJSON?.data?.errorMessage);
        },
      });
    });
  }
}

export default new SolanaAPI();
