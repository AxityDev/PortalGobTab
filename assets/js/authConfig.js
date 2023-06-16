 
// Config object to be passed to Msal on creation.
// For a full list of msal.js configuration parameters, 
// visit https://azuread.github.io/microsoft-authentication-library-for-js/docs/msal/modules/_authenticationparameters_.html
var msalConfig = {
  auth: {
    clientId: "3a142803-f762-4135-b12b-f74e5672c084",
    authority: "https://login.microsoftonline.com/common",
    redirectUri: "https://portalgobiernotableros-appservice-replica.azurewebsites.net/",
    //redirectUri: "http://localhost:8080/LoginOF365/",
  },
  cache: {
    cacheLocation: "sessionStorage", // This configures where your cache will be stored
    storeAuthStateInCookie: false, // Set this to "true" if you are having issues on IE11 or Edge
  }
};  
  
// Add here the scopes to request when obtaining an access token for MS Graph API
// for more, visit https://github.com/AzureAD/microsoft-authentication-library-for-js/blob/dev/lib/msal-core/docs/scopes.md
var loginRequest = {
 /*  scopes: ["User.Read"] */
  scopes: ["https://graph.microsoft.com/.default"]
};

