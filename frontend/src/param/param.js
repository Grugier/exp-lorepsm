const host = 'http://localhost/exp-lorepsm/backend/';
//const host = 'https://exp-lorepsm.fabiencasperot.fr/backend/';

export default {
    host: host,
    localStore: "Exp-lorePSM",

    // infosAppli: {
    //     utilisateur: {
    //         id: 0,
    //         admin: false,
    //         infos: {
    //             login: '',
    //             prenom: '',
    //             nom: '',
    //             photo: '',
    //             annee: '',
    //             diplome: '',
    //         }
    //     }
    // },

    // USER
    URL_getLoggedUser: host + 'api/user/getLoggedUser.php',

    // CONNEXION LINKEDIN
    URL_loginFromFrontend: host + 'api/linkedin/loginFromFrontend.php',
    URL_authLinkedIn: host + 'api/linkedin/authLinkedIn.php',

    // POST
    URL_getPostsList: host + 'api/post/getPostsList.php'

}