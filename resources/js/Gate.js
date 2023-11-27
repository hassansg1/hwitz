
/*export function getAuthRolesPermissions() {
   //axios.get('api/auth-roles-permissions').then(({ data }) => { return data });
   return 1111;
}*/

export default class Gate{

	constructor(vueAuth) { 
   	} 

	getAuthRolesPermissions(){
		return vueAuth;
	}
}