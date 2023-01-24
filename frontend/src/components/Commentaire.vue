<template>
    <section class="com">
        <div class="commentaire">
            <div class="leCom">
                <div class="entete-com">
                    <img :src="(props.lAuteur.photoProfil !== null) ? param.URL_userPictures + props.lAuteur.photoProfil : '/user-invite.png'"
                        :alt="props.lAuteur.nom">
                    <p class="nom">{{ props.lAuteur.prenom }} {{ props.lAuteur.nom }}</p>
                    <div class="suppression" v-if="(props.utilisateur.idUser != 0 && getProprieteCom())" @click="supprimerCom();">
                        <span class="poubelle"></span>
                    </div>
                </div>
                <div class="corp-com">
                    <div class="interagir">
                        <span class="coeur aime"></span>
                        <p>{{ props.commentaire.lesLike }}</p>
                    </div>
                    <div>
                        <p>{{ props.commentaire.textPost }}</p>
                        <p v-if="props.commentaire.datePost" class="date">Publié le {{
                            getDate(props.commentaire.datePost)
                        }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- 
        <div class="popupInteractions2" v-if="action" v-bind:class="{popupSignaler : !sonCom}">
            <div class="suppression" v-if="(sonCom || utilisateur.admin)" @click="supprimer = true; action=false">
                <span class="poubelle"></span>
                <p>Supprimer</p>
            </div>
            <div class="modification" @click="modifier = true; action = false" v-if="sonCom">
                <span class="stylo"></span>
                <p>Modifier</p>
            </div>
            <div class="signalement" v-if="((!sonCom) &&(!utilisateur.admin))" @click="$emit('signaler');action=false">
                <span class="drapeau"></span>
                <p>Signaler </p>
            </div>
        </div> -->

        <!-- <ModifierCommentaire :commentaire="$props.commentaire" :idUtilisateur="utilisateur.id" v-if="modifier"
            v-on:fermer="modifier = false" v-on:rechargement="$emit('rechargement')" />
        <SupprimerCommentaire :commentaire="$props.commentaire" v-if="supprimer" v-on:fermer="supprimer = false"
            v-on:rechargement="$emit('rechargement')" /> -->
    </section>
</template>

<script setup>
import param from "@/param/param";
import { ref } from 'vue'
import axios from 'axios'

const sonCom = ref(false);

const props = defineProps({
    commentaire: Object,
    lAuteur: Object,
    utilisateur: Object
});

const emit = defineEmits(['refreshsouvenir']);

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

function getProprieteCom() {
    if (props.lAuteur.idUser == props.utilisateur.idUser) {
        sonCom.value = true;
        return true
    } else {
        sonCom.value = false;
        return false
    }
}

function supprimerCom() {
    const params = new FormData();
    params.append('idPost', props.commentaire.idPost);
    params.append('idUser', props.utilisateur.idUser);

    //DEBUG
    for (const pair of params.entries()) {
        console.log(`${pair[0]}, ${pair[1]}`);
    }

    if (confirm('Voulez vous vraiment supprimer ce commentaire ?')) {
        axios.post(param.host + '/api/post/deletePost.php', params).then((promise) => {
            console.log('Delete : ' + promise);
            emit('refreshsouvenir');
        }).catch(error => console.log(error));
    }
}
</script>

<style scoped>
.nom {
    font-weight: bold;
}

/*Elements graphiques*/
.nom {
    white-space: nowrap;
    max-width: 16rem;
    overflow: hidden;
    background: linear-gradient(var(--darkGreen), var(--darkGreen));
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


/*Mise en page*/
.interagir {
    margin-left: 2.7rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 1rem;
}

.interagir p,
.corp-com p {
    margin: 0;
    max-width: 29rem;
}

.commentaire img {
    width: 4.5rem;
    height: 4.5rem;
    margin-left: 1.5rem;
    margin-right: 0.5rem;
    border-radius: 20rem;
}

.commentaire {
    border-radius: 2rem;
    padding-bottom: 1rem;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.com {
    position: relative;
}

.entete-com {
    display: flex;
    align-items: center;
}

.corp-com {
    display: flex;
}

.leCom {
    width: 100%;
}

.corp-com .date {
    font-style: italic;
    font-size: 1.5rem;
    margin-top: 1rem;
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

/*Mise en page du popup de confirmation*/
.confirmation {
    position: absolute;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(238, 237, 236, 0.4);
    z-index: 2;
}

.popupConfirmation {
    position: relative;
    top: 30%;
    left: 33%;
    width: 65rem;
    height: 14rem;
    background-color: #EEEDEC;
    border-radius: 2rem;
    z-index: 3;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-evenly;
}

.popupConfirmation>div {
    width: 70%;
    display: flex;
    justify-content: space-between;

}

.popupConfirmation p {
    font-size: 2rem;
    font-family: 'Public-Sans-Bold';
    margin: 0;
}

.popupConfirmation button {
    font-size: 1.8rem;
    padding: 1rem 2rem;
    width: 12rem;
    cursor: pointer;
}

.confirmer {
    background-color: #FE4154;
    color: #EEEDEC;
}

.annuler {
    background-color: #E8D3BF;
    color: black;
}
</style>
