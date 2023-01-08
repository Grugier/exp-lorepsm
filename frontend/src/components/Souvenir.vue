<template>
    <div class="bg">
        <!-- <div>
        <p @click="$emit('fermersouvenir')" class="close">X</p>
        <p>Bonjour je suis un souvenir lol</p>
    </div> -->
        <section>
            <div class="bloc-souvenir">
                <div class="gestionSouvenir">
                    <!-- <span class="modif" @click="interactions(souvenir.parent)"
                    v-if="utilisateur.id != 73 && utilisateur.id != 0"></span> -->
                    <span @click="$emit('fermersouvenir')" class="fermer"></span>
                </div>
                <div class="souvenir">
                    <div class="contenuSouvenir">
                        <div class="entete-souvenir">
                            <!-- <img :src="(souvenir.parent.lAuteur.photoProfil !== null) ? souvenir.parent.lAuteur.photoProfil : 'http://loremmi.viensenmmi.com/jsonLoremmi/imagesVisite/user-invite.png'"
                            :alt="souvenir.parent.lAuteur.nom"> -->
                            <img src="https://dam.malt.com/zd82z90rq7y4lkld76cz?gravity=face&func=face&face_margin=60&w=440&h=440&force_format=webp">
                            <p class="nom">{{ getAuteur.nom }} {{ getAuteur.prenom }}</p>
                            <p>- Promo {{ getAuteur.promo }}</p>

                        </div>
                        <div class="corp-souvenir">
                            <div class="interagir">
                                <!-- <span class="coeur" @click="like(souvenir.parent.idPost)"
                                v-bind:class="{ aimeFull: aime, aime: !aime }"></span>
                            <p>{{ souvenir.parent.lesJAime.length }}</p> -->
                            </div>
                            <div class="contenu-souvenir">
                                <p class="texteSouvenir">{{ souvenir.textPost }}</p>
                                <!-- <img :src="host + 'documentsSouvenirs/' + souvenir.docSVN"
                                :alt="souvenir.parent.lAuteur.nom" class="preview"
                                v-if="checkType(souvenir.docSVN, 'image')" @click="zoomImage = !zoomImage">
                            <div class="zoom" v-if="zoomImage">
                                <span class="fermerZoom" @click="zoomImage = false"></span>
                                <img :src="host + 'documentsSouvenirs/' + souvenir.docSVN"
                                    :alt="souvenir.parent.lAuteur.nom" @click="zoomImage = !zoomImage">
                            </div> -->
                                <!-- <audio :src="host + 'documentsSouvenirs/' + souvenir.docSVN"
                                v-if="checkType(souvenir.docSVN, 'audio')" controls class="lecteurAudio"></audio>
                            <iframe :src="lienVideo(souvenir.docSVN)" v-if="checkType(souvenir.docSVN, 'video')"
                                allowfullscreen></iframe> -->
                            </div>
                        </div>
                    </div>
                    <div class="listeCommentaire">
                        <!-- <AfficherCommentaires v-for="commentaire in souvenir.lesCommentaires"
                        v-bind:key="commentaire.idPost" :commentaire="commentaire" v-on:signaler="signalement = true"
                        v-on:dejasignale="GestionSignaleCom" v-on:rechargement="getPost" :fermeture="fermeture" /> -->
                    </div>
                    <!-- <button v-show="((utilisateur.id !== 0) && (!utilisateur.admin) && (utilisateur.id != 73))"
                    @click="commenter = true">Commenter...</button> -->
                </div>
            </div>

            <div class="popupInteractions" v-if="action" v-bind:class="{ popupSignaler: !sonSouvenir }">
                <!-- <div class="suppression" v-if="(sonSouvenir || utilisateur.admin)"
                @click="supprimer = true; action = false">
                <span class="poubelle"></span>
                <p>Supprimer</p>
            </div>
            <div class="modification" @click="modifier = true; action = false"
                v-if="(sonSouvenir || utilisateur.admin)">
                <span class="stylo"></span>
                <p>Modifier</p>
            </div>
            <div class="signalement" v-if="((!sonSouvenir) && (!utilisateur.admin))"
                @click="signalement = true; action = false">
                <span class="drapeau"></span>
                <p>Signaler </p>
            </div> -->
            </div>

            <!-- <PopupConfirmation v-if="signalement" v-on:fermer="signalement = false" v-on:signaler="getPost"
            :Type="TypeMessage" :idUtilisateur="utilisateur.id" :idPost="souvenir.parent.idPost"
            :dejaSignale="dejaSignale" />
        <ModifierSouvenir :idSouvenir="souvenir.parent.idPost" v-on:fermer="modifier = false" v-on:reload="getPost"
            v-if="modifier" />
        <SupprimerSouvenir :idSouvenir="souvenir.parent.idPost" v-on:fermer="supprimer = false" v-on:reload="getPost"
            v-if="supprimer" />
        <AjoutCommentaire :idSouvenir="souvenir.parent.idPost" :utilisateur="utilisateur" v-if="commenter"
            v-on:fermer="updateVuePost" /> -->

        </section>
    </div>
</template>

<script setup>
import { reactive, computed } from 'vue';

const souvenir = reactive({
    coords: "0.3;0.2;0.2",
    dateSvn: null,
    lesCommentaires: [
        {
            idPost: "3",
            datePost: "2022-12-30 20:43:45",
            textPost: "fgdgfdgfdgf",
            lAuteur: "2",
            lesLike: "1",
            userALike: true
        }],
    lesDocuments: [
        {
            idDoc: "1",
            typeDoc: "0",
            nomDoc: "fdsfds.jpg",
            leSouvenir: null
        }
    ],
    idPost: "1",
    datePost: "2022-12-22 00:00:00",
    textPost: "FEUR MEC, FEUR, t'as compris ? FEUR HAHA JE SUIS TROP DRÔLE",
    lAuteur: "1",
    lesLike: "2",
    userALike: true
})

const auteurs = reactive({
    listAuteurs: [
        {
            idUser: "1", typeUser: "1", prenom: "Malo", nom: "Lerenard", photoProfil: null, promo: "2023", lesSouvenirs: [], lesCommentaires: [], lesLike: []
        }, {
            idUser: "2", typeUser: "1", prenom: "Malo", nom: "Lerenard", photoProfil: null, promo: null, lesSouvenirs: [], lesCommentaires: [], lesLike: []
        },
    ]
});

//Computed pour avoir les infos de l'auteur d'un souvenir
const getAuteur = computed(() => {
    return auteurs.listAuteurs.find((a) => a.idUser == souvenir.lAuteur)
});


</script>

<style scoped>
section {
    padding-top: 10rem;
}
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
.modif {
    background: url(../assets/elements-graphiques/modifierPosts.png) no-repeat;
    background-size: contain;
    background-position: center;
    display: block;
    width: 3.8rem;
    height: 3.8rem;
    cursor: pointer;
}

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
    width: max-content;
    background: linear-gradient(#E59845, #E59845);
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

.icone-commentaire {
    background: url(../assets/elements-graphiques/commenter.svg) no-repeat;
    background-size: contain;
    background-position: center;
    display: block;
    width: 2.1rem;
    height: 2rem;
    margin-left: 90%;
    cursor: pointer;
}

button {
    border: none;
    font-size: 1.8rem;
    padding: 1.4rem 11rem;
    text-align: center;
    border-radius: 4rem;
    background-color: #E59845;
    color: #EEEDEC;
    margin-top: 1.5rem;
    cursor: pointer;
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
.corp-souvenir p {
    margin: 0 0 1rem 0;
    max-width: 75%;
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
    margin-top: 7rem;
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

audio {
    margin-bottom: 1rem;
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

/*ADD SAOUVENIR*/
/* .fleche>span {
    width: max-content;
    background: linear-gradient(#E59845, #E59845);
    background-size: auto .5em;
    background-position: bottom;
    background-repeat: no-repeat;
  }

  .fleche:after {
    display: inline-block;
    content: "";
    width: 3rem;
    height: 1.3rem;
    background: url(../assets/elements-graphiques/fleche.svg) no-repeat;
    margin-left: 0.4rem;
  }

  .add-souvenir {
    background-color: #EEEDEC;
    width: 65rem;
    height: 38.5rem;
    border-radius: 2rem;
    position: absolute;
    top: 20%;
    left: 3%;
    padding-left: 5rem;
    padding-right: 5rem;
  }

  .add-souvenir img {
    width: 4.5rem;
    height: 4.5rem;
    margin-right: 3rem;
  }

  .fermer {
    background: url(../assets/elements-graphiques/fermer.svg) no-repeat;
    background-size: contain;
    background-position: center;
    display: block;
    width: 1.4rem;
    height: 1.4rem;
  }

  .add-souvenir p:first-of-type {
    color: #756464;
  }

  .haut {
    display: flex;
    align-items: flex-start;
    margin-top: 3rem;
  }

  textarea {
    width: 39rem;
  }

  .bas {
    display: flex;
    align-items: center;
    margin-top: 7rem;
  }

  .fermer {
    margin-top: 2rem;
    margin-left: 99%;
  } */

/*Popup d'interactions*/
.stylo {
    background: url(../assets/elements-graphiques/stylo.svg) no-repeat;
    background-size: contain;
    background-position: center;
    width: 1.7rem;
    height: 1.7rem;
    display: block;
    margin-right: 0.7rem;
}

.poubelle {
    background: url(../assets/elements-graphiques/poubelle.svg) no-repeat;
    background-size: contain;
    background-position: center;
    width: 1.6rem;
    height: 2.1rem;
    display: block;
    margin-right: 0.7rem;
}

.drapeau {
    background: url(../assets/elements-graphiques/drapeau.svg) no-repeat;
    background-size: contain;
    background-position: center;
    width: 1.6rem;
    height: 2rem;
    display: block;
    margin-right: 0.7rem;
}

.signalement p {
    color: #FE4154;
}

.popupInteractions>div {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.suppression,
.modification {
    width: 80%;
}

.suppression {
    margin-bottom: 1.8rem;
}

.signalement {
    width: auto;
}

.suppression p {
    color: #FE4154;
}

.popupInteractions p {
    margin: 0;
}

.popupInteractions {
    width: 15rem;
    height: 11rem;
    border-radius: 2rem;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 7.5%;
    left: 60.5%;
    background-color: #EEEDEC;
    z-index: 1;
}

.popupSignaler {
    height: 4.3rem;
}

/* SOUVENIR A VIRER APRES */
.bulleSouvenir {
    height: 3.5rem;
    width: 3.5rem;
    background-color: #E59845;
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;

    position: absolute;
    top: 50%;
    left: 35%;
}

/* Smartphone */
@media only screen and (max-width: 480px) {
    .popupInteractions {
        width: 70%;
        top: 8rem;
        left: .6rem;
    }

    .popupInteractions>div {
        width: auto;
    }

    .contenu {
        margin-top: 1rem;
    }

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
