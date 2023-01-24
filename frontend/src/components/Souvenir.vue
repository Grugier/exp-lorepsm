<template>
    <div class="bg">
        <div class="bloc-souvenir">
            <div class="gestionSouvenir">
                <span @click="$emit('fermersouvenir')" class="fermer"></span>
            </div>
            <div class="souvenir">
                <div class="contenuSouvenir">
                    <div class="entete-souvenir">
                        <img :src="(getAuteur().photoProfil !== null) ? param.URL_userPictures + getAuteur().photoProfil : '/user-invite.png'"
                            :alt="getAuteur().nom">
                        <p class="nom">{{ getAuteur().prenom }} {{ getAuteur().nom }}</p>
                        <div class="suppression" v-if="(userCo.idUser != 0 && sonSouvenir)"
                            @click="supprimerSouvenir()">
                            <span class="poubelle"></span>
                        </div>
                    </div>
                    <div class="corp-souvenir">
                        <div class="interagir">
                            <span class="coeur aime"></span>
                            <p>{{ souvenir.lesLike }}</p>
                        </div>
                        <div class="contenu-souvenir">
                            <p v-if="souvenir.dateSvn">Le {{ getDate(souvenir.dateSvn) }} : </p>
                            <p class="texteSouvenir">{{ souvenir.textPost }}</p>
                            <img :src="param.URL_userDocuments + souvenir.lesDocuments[0].nomDoc" :alt="getAuteur().nom"
                                class="preview" v-if="souvenir.lesDocuments[0] && souvenir.lesDocuments[0].typeDoc == 0"
                                @click="zoomImage = !zoomImage">
                            <div class="zoom" v-if="zoomImage">
                                <span class="fermerZoom" @click="zoomImage = false"></span>
                                <img :src="param.URL_userDocuments + souvenir.lesDocuments[0].nomDoc"
                                    :alt="getAuteur().nom" @click="zoomImage = !zoomImage">
                            </div>
                            <iframe :src="getLienVideo(souvenir.lesDocuments[0].nomDoc)"
                                v-if="souvenir.lesDocuments[0] && souvenir.lesDocuments[0].typeDoc == 2 && souvenir.lesDocuments[0].nomDoc.includes('youtu')"
                                allowfullscreen></iframe>
                            <p v-if="souvenir.datePost" class="date">Publié le {{ getDate(souvenir.datePost) }}</p>
                        </div>
                    </div>
                </div>
                <div class="listeCommentaire">
                    <Commentaire v-for="commentaire in souvenir.lesCommentaires" :commentaire="commentaire"
                        :lAuteur="getAuteurCom(commentaire)" :utilisateur="userCo" @refreshsouvenir="refreshSouvenir();" />
                </div>
                <button v-show="userCo.idUser != 0" @click="commenter = true">Commenter...</button>
            </div>
        </div>

        <AjoutCommentaire :souvenir="souvenir" :lAuteur="getAuteur()" :utilisateur="userCo" v-if="commenter"
            @fermerajoutcom="commenter = false; refreshSouvenir();" />
    </div>
</template>

<script setup>
import { reactive, ref, watch, getCurrentInstance } from 'vue';
import axios from 'axios'
import param from "@/param/param";
import Commentaire from '../components/Commentaire.vue'
import AjoutCommentaire from '../components/AjoutCommentaire.vue'
import { useExploreStore } from "@/stores/exploreStore";
import { storeToRefs } from 'pinia';

// On crée une instance du store de l'application
const store = useExploreStore();
const { userCo } = storeToRefs(store);

let souvenir = reactive({
    coords: "",
    dateSvn: "",
    lesCommentaires: [
        {
            idPost: "",
            datePost: "",
            textPost: "",
            lAuteur: "",
            lesLike: "",
            userALike: false
        }],
    lesDocuments: [
        {
            idDoc: "",
            typeDoc: "",
            nomDoc: "",
            leSouvenir: null
        }
    ],
    idPost: "",
    datePost: "",
    textPost: "",
    lAuteur: "",
    lesLike: "",
    userALike: true
})

let auteurs = reactive({
    listAuteurs: [
        {
            idUser: "", typeUser: "", prenom: "", nom: "", photoProfil: null, promo: "", lesSouvenirs: [], lesCommentaires: [], lesLike: []
        }
    ]
});

//Ref
const zoomImage = ref(false);
const commenter = ref(false);
const sonSouvenir = ref(false);

const instance = getCurrentInstance();
const props = defineProps({
    idSouvenir: Number
});
const emit = defineEmits(['fermersouvenir', 'refresh']);
//Computed pour avoir les infos de l'auteur d'un souvenir
function getAuteur() {
    return auteurs.listAuteurs.find((a) => a.idUser == souvenir.lAuteur)
}

//Fonction pour avoir les infos de l'auteur d'un commentaire
function getAuteurCom(com) {
    return auteurs.listAuteurs.find((a) => a.idUser == com.lAuteur)
}

//Fonction pour formater la date
function getDate(d) {
    const date = new Date(d);
    let mois;
    switch (date.getMonth()) {
        case 0:
            mois = "janvier";
            break;
        case 1:
            mois = "février";
            break;
        case 2:
            mois = "mars";
            break;
        case 3:
            mois = "avril";
            break;
        case 4:
            mois = "mai";
            break;
        case 5:
            mois = "juin";
            break;
        case 6:
            mois = "juillet";
            break;
        case 7:
            mois = "août";
            break;
        case 8:
            mois = "septembre";
            break;
        case 9:
            mois = "octobre";
            break;
        case 10:
            mois = "novembre";
            break;
        case 11:
            mois = "décembre";
            break;
        default:
            break;
    }

    return date.getDate() + " " + mois + " " + date.getFullYear();
}

//Formatage de l'url de la vidéo
function getLienVideo(url) {
    //Seul les vidéo de youtube sont acceptée et il existe 2 formats : l'url direct et le partage
    if (url.includes("youtube.com")) {
        return url.replace("watch?v=", "embed/");
    } else if (url.includes("youtu.be")) {
        let videoId = url.split("/").pop();
        return `https://www.youtube.com/embed/${videoId}`;
    } else {
        return
    }
}

//Quand on viens d'ajouter ou supprimer un commentaire -> refresh le composant
function refreshSouvenir() {
    clearSouvenir();
    loadSouvenir(props.idSouvenir);
}

//Surveiller le clique sur un souvenir
watch(
    () => props.idSouvenir,
    (idSouvenir) => {
        clearSouvenir();
        loadSouvenir(idSouvenir);
    }
)

function loadSouvenir(idSouvenir) {
    //Charger les infos du souvenir
    axios.get(param.host + '/api/post/getPostInfo.php?idPost=' + idSouvenir).then((rep) => {
        auteurs.listAuteurs = rep.data.auteurs;
        souvenir = rep.data.souvenirs[0];
        if (getAuteur().idUser == userCo.value.idUser) {
            sonSouvenir.value = true;
        } else {
            sonSouvenir.value = false;
        }
        instance?.proxy?.$forceUpdate();
    });
}

function clearSouvenir() {
    souvenir = {
        coords: "",
        dateSvn: "",
        lesCommentaires: [
            {
                idPost: "",
                datePost: "",
                textPost: "",
                lAuteur: "",
                lesLike: "",
                userALike: false
            }],
        lesDocuments: [
            {
                idDoc: "",
                typeDoc: "",
                nomDoc: "",
                leSouvenir: null
            }
        ],
        idPost: "",
        datePost: "",
        textPost: "",
        lAuteur: "",
        lesLike: "",
        userALike: true
    };

    auteurs.listAuteurs = [
        {
            idUser: "", typeUser: "", prenom: "", nom: "", photoProfil: null, promo: "", lesSouvenirs: [], lesCommentaires: [], lesLike: []
        }
    ];
}

function supprimerSouvenir() {
    const params = new FormData();
    params.append('idPost', props.idSouvenir);
    params.append('idUser', userCo.value.idUser);

    if (confirm('Voulez vous vraiment supprimer ce souvenir ?')) {
        axios.post(param.host + '/api/post/deletePost.php', params).then((promise) => {
            emit('fermersouvenir');
            emit('refresh');
        });
    }
}

</script>

<style scoped>
.bg {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(94, 94, 94, 0.6);
    z-index: 3;
    display: flex;
    justify-content: center;
}

/*HUD*/
/*Typo*/
.nom {
    font-weight: bold;
}

/*Elements graphiques*/
.fermer {
    background: url(../assets/elements-graphiques/fermer.svg) no-repeat;
    background-size: contain;
    background-position: center;
    display: block;
    width: 2.8rem;
    height: 2.8rem;
    cursor: pointer;
    margin-left: 1rem;
}

.nom {
    white-space: nowrap;
    max-width: 16rem;
    overflow: hidden;
    background: linear-gradient(var(--lightGreen), var(--lightGreen));
    background-size: auto .5em;
    background-position: bottom;
    background-repeat: no-repeat;
}

.aime {
    background: url(../assets/elements-graphiques/aime.svg) no-repeat;
}

.aimeFull {
    background: url(../assets/elements-graphiques/aime-full.svg) no-repeat;
}

.coeur {
    background-size: contain;
    background-position: center;
    width: 2.3rem;
    height: 1.7rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
}

button {
    border: none;
    font-size: 1.8rem;
    padding: 1.4rem 11rem;
    text-align: center;
    border-radius: 4rem;
    background-color: var(--lightGreen);
    color: #EEEDEC;
    margin-top: 1.5rem;
    cursor: pointer;
}

/*Mise en page*/
.interagir {
    margin-left: 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 1rem;
}

.interagir p,
.corp-souvenir p {
    margin: 0 0 1rem 0;
    /* max-width: 75%; */
}

.corp-souvenir {
    display: flex;
}

.entete-souvenir img {
    width: 4.5rem;
    height: 4.5rem;
    margin-left: 1.5rem;
    margin-right: 0.5rem;
    border-radius: 20rem;
}

.souvenir {
    background-color: #EEEDEC;
    width: 36.5rem;
    max-height: 66.5vh;
    border-radius: 2rem;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.bloc-souvenir {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    margin-top: 17rem;
}

.entete-souvenir {
    display: flex;
    align-items: center;
    margin-top: 1rem;
}

.gestionSouvenir {
    display: flex;
    margin-bottom: 1rem;
}

.preview {
    max-height: 20rem;
    max-width: 28rem;
    border-radius: 2rem;
    margin-bottom: 1rem;
}

.contenu-souvenir>img {
    cursor: pointer;
}

iframe {
    width: 25rem;
    height: 15rem;
    margin-bottom: 1rem;
}

.zoom {
    position: fixed;
    z-index: 5;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(238, 237, 236, 0.3);

}

.zoom img {
    max-width: 80%;
    max-height: 80%;
    animation: zoomIn .7s;
}

.fermerZoom {
    background: url(../assets/elements-graphiques/fermer.svg) no-repeat;
    background-size: contain;
    background-position: center;
    display: block;
    width: 3rem;
    height: 3rem;
    position: absolute;
    right: 20%;
    top: 15%;
    cursor: pointer;
}

.contenuSouvenir {
    width: 100%;
}

.listeCommentaire {
    margin-top: 2rem;
    max-height: 47.3rem;
    overflow-y: scroll;
    overflow-x: hidden;
    width: 100%;
}

.contenu-souvenir {
    width: 75%;
    border-bottom: 2px solid #302A2A;
}

.date {
    font-style: italic;
    font-size: 1.5rem;
}

/*Popup d'interactions*/

.poubelle {
    background: url(../assets/elements-graphiques/poubelle.svg) no-repeat;
    background-size: contain;
    background-position: center;
    width: 1.6rem;
    height: 2.1rem;
    display: block;
    margin-right: 0.7rem;
}

.suppression {
    cursor: pointer;
    margin-left: 10rem;
}

.suppression p {
    color: #FE4154;
}

/* Smartphone */
@media only screen and (max-width: 480px) {
    .bloc-souvenir {
        margin-top: 8rem;
    }

    .souvenir {
        max-height: 77vh;
    }

    .preview {
        max-height: 15rem;
    }
}
</style>
