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
    URLgetLoggedUser: host + 'api/user/getLoggedUser.php',

    // CONNEXION LINKEDIN
    URLloginFromFrontend: host + 'api/linkedin/loginFromFrontend.php',
    URLauthLinkedIn: host + 'api/linkedin/authLinkedIn.php',

}