const prefix = "api/";
const version  = "v1/"
const api = prefix + version
export const routes = {
  "customers":`${api}customers`,
  "services":`${api}services`,
  "transactions":`${api}transactions`,
  "users":`${api}users`,
  "change_password":`${prefix}user/password`,
  "register":`${api}register`,
}