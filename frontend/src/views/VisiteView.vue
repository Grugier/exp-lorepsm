<template>
    <a-scene raycaster="objects: .clickable" removecursorwheninvr cursor="rayOrigin: mouse" renderer="colorManagement: true;"
        background="color: #ECECEC">
        <a-assets>
            <a-asset-item id="stgic" src="/stgi_c__mixed_v1.glb"></a-asset-item>
        </a-assets>
        <a-entity id="stgic-entity"></a-entity>

        <!-- ENVIRONNEMENT DE TEST EN ATTENDANT LE BATIMENT -->

        <!-- Plafond -->
        <a-plane static-body position="-0.62674 6.967 -4.46685" rotation="90 0 0" width="35.99" height="36.95"
            color="#ffffff" material="shader: flat;"></a-plane>

        <!-- Sol -->
        <a-plane static-body position="-0.62674 -0.1 -4.46685" rotation="-90 0 0" width="35.99" height="36.95"
            color="#999999" shadow></a-plane>

        <!-- Murs -->
<!--        <a-box static-body position="16.61 3.5 -4.57041" geometry="height: 8; width: 37.27" rotation="0 90 0"></a-box>-->
<!--        <a-box static-body position="-17.857 3.5 -4.35469" geometry="height: 8; width: 37.19" rotation="0 90 0"></a-box>-->
<!--        <a-box static-body position="-0.43974 3.5 7.604" geometry="height: 8; width: 35.52"></a-box>-->
<!--        <a-box static-body position="-0.4882 3.5 -22.5" geometry="height: 8; width: 35.59"></a-box>-->

<!--        <a-box static-body position="9 1.298195 0" geometry="height: 2.59639; width: 1"></a-box>-->
<!--        <a-box static-body position="8 1 0" geometry="height: 2; width: 1"></a-box>-->
<!--        <a-box static-body position="7 .5 0" geometry="height: 1; width: 1"></a-box>-->

        <a-entity light="type: ambient; intensity: 0.35;"></a-entity>
        <a-entity light="type: ambient; intensity: 0.35;"></a-entity>
        <a-entity light="type: directional;
                             castShadow: true;
                             intensity: 0.25;" position="-5 3 1.5"></a-entity>

        <!-- FIN ENVIRONNEMENT DE TEST -->

        <!-- Caméra -->
        <a-entity id="joueur" kinema-body="radius: 0.4;" movement-controls="fly: false" position="-3.357 -0.1 7.255">
            <a-entity camera position="0 1.6 0" look-controls="mouseEnabled: true"></a-entity>
        </a-entity>

        <!-- Souvenir statique de test -->
        <!-- <a-entity souvenir id="sphere" geometry="primitive: sphere; radius: 0.25"
                material="color: #99BF1C; shader: flat"
                position="7 2 0"
                light="type: point; intensity: 0.075"
                class="clickable"
              ></a-entity> -->
    </a-scene>
    <!-- <button @click="checkConnecte" class="addSouvenirBouton" v-if="placerSouvenir == false">+</button>-->
    <button class="addSouvenirBouton" v-if="placerSouvenir == false && userCo.idUser != 0"
        @click="placerSouvenir = true">+</button>

    <div class="placerSouvenir" v-if="placerSouvenir">
        <div class="enTete">
            <p>Placez le point à l'endroit de votre choix.</p>
        </div>
        <span class="bulle"></span>
        <button class="publierBtn" @click="addSouvenirPopup = true; placerSouvenir = false">Valider</button>
        <button class="annulerBtn" @click="placerSouvenir = false">Annuler</button>
    </div>

    <div class="bg2" v-if="addSouvenirPopup">
        <div class="add-souvenir" v-bind:class="{ ajouterUnDoc: ajouterUnDoc }">
            <span class="fermer" @click="resetInfos(); addSouvenirPopup = false"></span>
            <div class="haut">
                <img :src="'http://localhost/exp-lorepsm/backend/documentsUGC/profilePicUsers/' + userCo.photoProfil" :alt="userCo.nom">
                <div class="hautInfos">
                    <textarea v-model="souvenirAjout.textPost" rows="8" cols="80"
                        placeholder="Quel souvenir partager ? La pièce jointe doit être une photo."
                        required v-bind:class="{ texteImage: !ajouterUnDoc }"></textarea>
                    <input v-model="souvenirAjout.date" type="date" required class="dateSouvenir">
                </div>
            </div>
            <img :src="imageData" :alt="imageData" class="preview" v-if="imageJointe == true">
            <iframe :src="videoData" v-if="urlJoint" allowfullscreen></iframe>
            <div class="bas" v-bind:class="{ basImage: !imageJointe, basURL: urlJoint }">
                <p @click="ajouterUnDoc = true" v-bind:class="{ invisible: ajouterUnDoc }" class="fleche"><span>Ajouter un
                        document</span></p>
                <p @click="supprimerDocument" v-bind:class="{ visibilite: !pieceJointe }" class="fleche"><span>Supprimer
                        le document</span></p>
                <button @click="chargementInfosSouvenir">Publier</button>
            </div>
            <div class="pieceJointe" v-if="ajouterUnDoc">
                <input type="file" @change="previewDocument"
                    :accept="'.png,.jpg,.jpeg' + ((!utilisateur.admin) ? ',.mp3,.wav,.ogg' : '')" ref="fichier">
                <div>
                    <p>Ou</p>
                    <label for="url">URL pour une vidéo</label>
                    <input @change="ajoutURL" ref="champUrl" type="url" name="url" id="url">
                </div>
            </div>
        </div>
    </div>

    <Souvenir v-show="souvenir.open" @fermersouvenir="souvenir.open = false" :idSouvenir="souvenir.idClicked" />
</template>

<style>
a-scene {
    width: 100%;
    height: 100vh;
}
</style>
<script setup>
import { reactive, ref, onMounted } from 'vue'
import axios from 'axios'
import param from "@/param/param";
import Souvenir from '../components/Souvenir.vue'
import { useExploreStore } from "@/stores/exploreStore";
import { storeToRefs } from 'pinia';

// On crée une instance du store de l'application
const store = useExploreStore();
const { userCo } = storeToRefs(store);

//Data
let souvenir = reactive({ open: false, idClicked: null })
let placerSouvenir = ref(false);
let addSouvenirPopup = ref(false);
//let lstSouvenirs = {};
let souvenirAjout = reactive({
    coords: "",
    dateSvn: "",
    lesDocuments: [
        {
            typeDoc: "",
            nomDoc: ""
        }
    ],
    textPost: "",
});

//Placer les souvenirs
function addSpot(spot) {
    console.log("Spot : ", spot);
    const coords = spot.coordsPost.split(';');

    var sceneEl = document.querySelector('a-scene');
    var entityEl = document.createElement('a-entity');
    entityEl.setAttribute('geometry', {
        primitive: 'sphere',
        radius: 0.25
    });
    entityEl.setAttribute('material', {
        color: '#99BF1C',
        shader: "flat"
    });
    // entityEl.setAttribute('light', {
    //     type: 'point',
    //     intensity: "0.075"
    // });
    entityEl.setAttribute('position', { x: coords[0], y: coords[1], z: coords[2] });
    entityEl.setAttribute('class', 'clickable');
    entityEl.setAttribute('souvenir', '');
    entityEl.setAttribute('id', spot.idPost);

    sceneEl.appendChild(entityEl);
}

//Si on cancel l'ajout de souvenir
function resetInfos() {

}

onMounted(() => {
    //Pour charger un modèle
    document.querySelector("#stgic-entity").setAttribute("gltf-model", "#stgic");

    //Charger la liste des souvenirs
    axios.get(param.host + '/api/post/getPostsList.php').then((list) => {
        //lstSouvenirs = list;

        list.data.forEach(p => {
            //console.log('Post : ', p);
            addSpot(p);
        });
        //console.log("Lst souvenir :", lstSouvenirs);
    });
});

//===A-frame===//
AFRAME.registerComponent("souvenir", {
    init: function () {
        const el = this.el;

        //Clique sur le souvenir
        el.addEventListener("click", function () {
            console.log('Souvenir cliqué !');
            souvenir.idClicked = el.getAttribute('id');
            souvenir.open = true;
        });
    }
});

</script>
<style scoped>
@-ms-viewport {
    width: device-width;
}

/*Ajout souvenir popup*/
.addSouvenirBouton {
    background-color: var(--lightGreen);
    border-radius: 50%;
    border: none;
    position: absolute;
    right: 5%;
    bottom: 5%;
    color: #EEEDEC;
    width: 5.8rem;
    height: 5.8rem;
    font-size: 5rem;
    cursor: pointer;
}
.fleche>span {
  width: max-content;
  background: linear-gradient(var(--darkGreen), var(--darkGreen));
  background-size: auto .5em;
  background-position: bottom;
  background-repeat: no-repeat;
  cursor: pointer;
}

.fleche:after {
  display: inline-block;
  content: "";
  width: 3rem;
  height: 1.3rem;
  background: url(../assets/elements-graphiques/fleche.svg) no-repeat;
  margin-left: 0.4rem;
  cursor: pointer;
}

.add-souvenir {
  background-color: #EEEDEC;
  width: 65rem;
  height: 41rem;
  border-radius: 2rem;
  padding-left: 5rem;
  padding-right: 5rem;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  position: relative;
  z-index: 3;
}

.haut img {
  width: 4.5rem;
  height: 4.5rem;
  margin-right: 3rem;
  border-radius: 20rem;
}

.fermer {
  background: url(../assets/elements-graphiques/fermer.svg) no-repeat;
  background-size: contain;
  background-position: center;
  display: block;
  width: 1.4rem;
  height: 1.4rem;
  position: absolute;
  right: 5%;
  top: 5%;
  cursor: pointer;
}
.bg2 {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(238, 237, 236, 0.4);
  z-index: 3;
  display: flex;
  justify-content: center;
  align-items: center;
}
.haut p:first-of-type {
  color: #756464;
}

.haut {
  display: flex;
  align-items: flex-start;
  margin-top: 4rem;
}

textarea {
  width: 45rem;
  height: 10rem;
}

.texteImage {
  height: 14rem;
}

.bas {
  display: flex;
  align-items: center;
  margin-top: 2rem;
}

.basImage {
  margin-top: 9rem;
}

.basAudio {
  margin-top: 6.6rem;
}

.basURL {
  margin-top: 0;
}

.add-souvenir button {
  border: none;
  font-size: 1.8rem;
  padding: 1.6rem 4rem;
  text-align: center;
  border-radius: 4rem;
  width: 16rem;
  background-color: var(--lightGreen);
  color: #EEEDEC;
  position: absolute;
  right: 5%;
  cursor: pointer;
}

.preview {
  height: 10rem;
  width: auto;
  margin-top: 1.5rem;
  margin-left: 7.8rem;
  border-radius: 2rem;
}

iframe {
  width: 15rem;
  height: 10rem;
  margin-top: 1.5rem;
  margin-left: 7.8rem;
}

.lecteurAudio {
  margin-top: 1.5rem;
  margin-left: 7.8rem;
}

.hautInfos {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.dateSouvenir {
  margin-top: 1.5rem;
}

.pieceJointe {
  display: flex;
  flex-direction: column;
}

.add-souvenir input:not([type="file"]) {
  box-sizing: border-box;
  padding: 1rem 1rem;
  border-radius: 2rem;
  border: none;
}

.add-souvenir textarea {
  box-sizing: border-box;
  padding: 1.7rem 2.3rem;
  border-radius: 2rem;
  border: none;
}

.pieceJointe label {
  margin-right: 2rem;
}

.pieceJointe {
  margin-top: 2.5rem;
}

.pieceJointe p {
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  color: black;
}

.ajouterUnDoc {
  height: 54rem;
}

.invisible {
  display: none;
}

.visibilite {
  visibility: hidden;
}


/* Popup placer souvenir */
.bulle {
    height: 3.5rem;
    width: 3.5rem;
    background-color: var(--lightGreen);
    border-radius: 50%;
    display: inline-block;
    cursor: pointer;
    opacity: 0.6;
}

.placerSouvenir {
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.placerSouvenir button {
    border: none;
    font-size: 1.8rem;
    padding: 1.5rem 4rem;
    text-align: center;
    border-radius: 4rem;
    width: 16rem;
    color: #EEEDEC;
    cursor: pointer;
    position: absolute;
    bottom: 5%;
}

.publierBtn {
    right: 5%;
    background-color: var(--lightGreen);

}

.annulerBtn {
    left: 5%;
    background-color: #FE4154;
}

.enTete {
    position: fixed;
    left: 0;
    top: 4.5rem;
    width: 100vw;
    height: 12rem;
    background-color: #EEEDEC;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 3;
}

.enTete p {
    font-size: 2.4rem;
}

/*Responsive popup*/
/* Smartphone */
@media only screen and (max-width: 480px) {
    .add-souvenir {
        width: 100%;
        height: 85%;
        margin-top: 35%;
        padding-top: 4rem;
        padding-left: 2rem;
        padding-right: 2rem;
        overflow-y: scroll;
    }

    .hautInfos {
        width: 100%;
    }

    .add-souvenir textarea {
        width: 100%;
    }

    .bas {
        flex-direction: column;
    }

    .add-souvenir button {
        position: static;
        width: 100%;
    }

    .fleche {
        margin-left: -35%;
        margin-bottom: 4rem;
    }

    .pieceJointe {
        margin-bottom: 5rem;
    }

    .texteImage {
        height: 15rem;
    }

    .visibilite {
        display: none;
    }

    .add-souvenir input:not([type="file"]) {
        margin-top: 1rem;
    }

    audio {
        width: 25rem;
    }

    .enTete {
        padding: 2rem;
        text-align: center;
    }

}
</style>