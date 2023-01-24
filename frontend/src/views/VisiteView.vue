<template>
  <a-scene raycaster="objects: .clickable" removecursorwheninvr cursor="rayOrigin: mouse"
    renderer="colorManagement: true;" background="color: #ECECEC">
    <a-assets>
      <a-asset-item id="stgic" src="/stgi_c__mixed_v1.glb"></a-asset-item>
    </a-assets>
    <a-entity id="stgic-entity"></a-entity>

    <!-- COLLISIONS -->

    <!-- Sol RDC -->
    <a-box static-body
           position="-1.5 -.55 0" rotation="-90 0 0"
           width="14" height="22" material="opacity: 0"></a-box>

    <!-- Pente 1, 1er palier -->
    <a-box static-body position="-6.1 0.358 0.5" rotation="-60 0 0"
           width="2.3" height="2.96" material="opacity: 0"></a-box>

    <!-- Sol 1er palier -->
    <a-box static-body position="-6.9 1.03 -1.8" rotation="-90 0 0"
           width="4.5" height="2.55" material="opacity: 0"></a-box>

    <!-- Pente 2, 1er étage -->
    <a-box static-body position="-8 2. 0.7" rotation="-60 180 0"
           width="1.7" height="3.52" material="opacity: 0"></a-box>

    <!-- Sol 1er étage -->
    <a-box static-body position="-6.46 3 3" rotation="-90 0 0"
           depth=".5" width="5" height="2" material="opacity: 0"></a-box>

    <!-- Pente 3, 2e palier -->
    <a-box static-body position="-5.8 3.84 0.86" rotation="-60 0 0"
           depth=".5" width="2" height="3.25" material="opacity: 0"></a-box>

    <!-- Sol 2e palier -->
    <a-box static-body position="-6.9 4.66 -1.4" rotation="-90 0 0"
           depth=".5" width="5" height="2" material="opacity: 0"></a-box>

    <!-- Pente 4, 2e étage -->
    <a-box static-body position="-7.8 5.68 0.66" rotation="-59.4 180 0"
           depth=".5" width="1.9" height="3.4" material="opacity: 0"></a-box>

    <!-- Sol 2e étage escaliers -->
    <a-box static-body position="-6.321 6.4 3" rotation="-90 0 0"
           depth=".5" width="5.63" height="2.5" material="opacity: 0"></a-box>

    <!-- Sol 2e étage -->
    <a-box static-body
           position="13.45 6.2 -9.23" rotation="-90 0 0"
           depth="1" width="34.7" height="33.4" material="opacity: 0"></a-box>
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

    <!-- Caméra -->
    <a-entity id="joueur" kinematic-body="mass: 5; radius: 0.4;" movement-controls="speed: 0.2; fly: false; enabled:true;"
      position="-3.357 0 7.255">
      <a-entity camera position="0 1.6 0" look-controls="enabled:true;" id="camera"></a-entity>
    </a-entity>

  </a-scene>

  <button class="addSouvenirBouton" v-if="placerSouvenir == false && userCo.idUser != 0"
    @click="placerSouvenir = true; addPlacementSphere();">+</button>

  <div class="placerSouvenir" v-if="placerSouvenir">
    <div class="enTete">
      <p>Placez le point à l'endroit de votre choix.</p>
    </div>
    <button class="publierBtn"
      @click="addSouvenirPopup = true; placerSouvenir = false; removePlacementSphere(); disableMovements();">Valider</button>
    <button class="annulerBtn" @click="placerSouvenir = false; removePlacementSphere();">Annuler</button>
  </div>

  <div class="bg2" v-if="addSouvenirPopup">
    <div class="add-souvenir" v-bind:class="{ ajouterUnDoc: ajouterUnDoc }">
      <span class="fermer" @click="resetInfos(); enabledMovements(); addSouvenirPopup = false"></span>
      <div class="haut">
        <img :src="(userCo.photoProfil !== null) ? param.URL_userPictures + userCo.photoProfil : '/user-invite.png'"
          :alt="userCo.nom">
        <div class="hautInfos">
          <textarea v-model="souvenirAjout.textPost" rows="8" cols="80"
            placeholder="Quel souvenir partager ? La pièce jointe doit être une photo." required
            v-bind:class="{ texteImage: !ajouterUnDoc }"></textarea>
          <input v-model="souvenirAjout.dateSvn" type="date" required class="dateSouvenir">
        </div>
      </div>
      <img :src="imageData" :alt="imageData" class="preview" v-if="imageJointe">
      <iframe :src="videoData" v-if="urlJoint" allowfullscreen></iframe>
      <div class="bas" v-bind:class="{ basImage: !imageJointe, basURL: urlJoint }">
        <p @click="ajouterUnDoc = true" v-bind:class="{ invisible: ajouterUnDoc }" class="fleche"><span>Ajouter un
            document</span></p>
        <p @click="supprimerDocument" v-bind:class="{ visibilite: !pieceJointe }" class="fleche"><span>Supprimer
            le document</span></p>
        <button @click="ajoutSouvenir()">Publier</button>
      </div>
      <div class="pieceJointe" v-if="ajouterUnDoc">
        <input type="file" @change="previewDocument()" accept=".png,.jpg,.jpeg" ref="fichier" id="fichier">
        <div>
          <p>Ou</p>
          <label for="url">URL YouTube pour une vidéo</label>
          <input @change="ajoutURL()" ref="champUrl" type="url" name="url" id="url">
        </div>
      </div>
    </div>
  </div>

  <Souvenir v-show="souvenir.open" @fermersouvenir="souvenir.open = false; enabledMovements();"
    :idSouvenir="souvenir.idClicked" @refresh= "refreshScene();"/>
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
let urlJoint = ref(false);
let imageJointe = ref(false);
let ajouterUnDoc = ref(false);
let pieceJointe = ref(false);
let imageData = ref("");
let videoData = ref("");

let souvenirAjout = reactive({
  coords: "",
  dateSvn: "",
  textPost: "",
});

//Placer les souvenirs
function addSpot(spot) {
  const coords = spot.coordsPost.split(';');

  const sceneEl = document.querySelector('a-scene');
  const entityEl = document.createElement('a-entity');
  entityEl.setAttribute('geometry', {
    primitive: 'sphere',
    radius: 0.15
  });
  entityEl.setAttribute('material', {
    color: '#99BF1C',
    shader: "flat"
  });
  entityEl.setAttribute('position', { x: coords[0], y: coords[1], z: coords[2] });
  entityEl.setAttribute('class', 'clickable');
  entityEl.setAttribute('souvenir', '');
  entityEl.setAttribute('id', spot.idPost);
  entityEl.addEventListener("click", clickSouvenir);

  sceneEl.appendChild(entityEl);
}

//Ajout d'un souvenir
function ajoutSouvenir() {
  if (userCo.value.idUser != 0) {
    const params = new FormData();

    if (souvenirAjout.textPost !== "") {
      params.append('typePost', 0);
      params.append('idAuteur', userCo.value.idUser);
      params.append('textPost', souvenirAjout.textPost);
      params.append('dateSvn', souvenirAjout.dateSvn);
      params.append('coordsSvn', souvenirAjout.coords);

      //Gestion du format de la date
      var d = new Date();
      var date = d.getFullYear() + "-" + (d.getMonth() + 1).toString().padStart(2, '0') + "-" + d.getDate().toString().padStart(2, '0')
      params.append('datePost', date);

      if (imageJointe.value) {
        const input = document.querySelector('#fichier');
        params.append('docSvn', input.files[0]);
      } else if (urlJoint.value) {
        params.append('docSvn', videoData.value);
      }

      axios.post(param.host + '/api/post/createPost.php', params).then((promise) => {
        addSouvenirPopup.value = false;
        resetInfos();
        refreshScene();
        enabledMovements();
      });
    } else {
      alert("Contenu textuel vide !");
    }
  }
}

//Si on cancel l'ajout de souvenir
function resetInfos() {
  souvenirAjout.coords = "";
  souvenirAjout.dateSvn = "";
  souvenirAjout.textPost = "";
  supprimerDocument();
}

//Ajouter sphère placement
function addPlacementSphere() {
  const cameraEl = document.querySelector('#camera');
  const entityEl = document.createElement('a-entity');
  entityEl.setAttribute('geometry', {
    primitive: 'sphere',
    radius: 0.15
  });
  entityEl.setAttribute('material', {
    color: '#99BF1C',
    shader: "flat",
    transparent: true,
    opacity: 0.6
  });
  entityEl.setAttribute('position', { x: 0, y: 0, z: -2 });

  cameraEl.appendChild(entityEl);
}

//Supprimer la sphère de placement
function removePlacementSphere() {
  const sphere = document.querySelector('#camera > a-entity');
  const c = sphere.object3D.getWorldPosition(new THREE.Vector3());
  const str = c.x + ";" + c.y + ";" + c.z;
  souvenirAjout.coords = str;
  sphere.parentNode.removeChild(sphere);
}

function disableMovements() {
  const playerEl = document.querySelector('#joueur');

  playerEl.setAttribute('movement-controls', {
    fly: "false",
    enabled: false
  });
}

function enabledMovements() {
  const playerEl = document.querySelector('#joueur');

  playerEl.setAttribute('movement-controls', {
    fly: "false",
    enabled: true
  });
}

//Charger la liste des souvenirs
function addAllSpot() {
  axios.get(param.host + '/api/post/getPostsList.php').then((list) => {
    list.data.forEach(p => {
      addSpot(p);
    });
  });
}

//Vider la scène des souvenirs et la recharger
function refreshScene() {
  const s = document.querySelectorAll("[souvenir]");
  s.forEach(function (element) {
    element.remove();
  });
  addAllSpot();
}

//Pièces jointes
function previewDocument() {
  document.querySelector('#url').value = null;
  urlJoint.value = false;
  const input = document.querySelector('#fichier');
  //Récupération des champs
  if (input.files && input.files[0]) {
    if (input.files[0].type == 'image/png' || input.files[0].type == 'image/jpg' || input.files[0].type == 'image/jpeg') {
      imageJointe.value = true;
      pieceJointe.value = true;
      const reader = new FileReader();
      reader.onload = (e) => {
        imageData.value = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
}

function ajoutURL() {
  document.querySelector('#fichier').value = null;
  imageJointe.value = false;
  pieceJointe.value = true;
  urlJoint.value = true;

  /*Preview des vidéos suivant les cas*/
  const input = document.querySelector('#url');
  const url = input.value;

  //Seul les vidéo de youtube sont acceptée et il existe 2 formats : l'url direct et le partage
  if (url.includes("youtube.com")) {
    videoData.value = url.replace("watch?v=", "embed/");
  } else if (url.includes("youtu.be")) {
    let videoId = url.split("/").pop();
    videoData.value = `https://www.youtube.com/embed/${videoId}`;
  }
}

function supprimerDocument() {
  //On met toutes les variables pour l'affichage à faux
  pieceJointe.value = false;
  imageJointe.value = false;
  urlJoint.value = false;
  ajouterUnDoc.value = false;

  //On vide les liens des documemnts
  imageData.value = null,
    videoData.value = null
}

function clickSouvenir() {
  souvenir.idClicked = parseInt(this.getAttribute('id'));
  souvenir.open = true;
  disableMovements();
}

onMounted(() => {
  //Pour charger un modèle
  document.querySelector("#stgic-entity").setAttribute("gltf-model", "#stgic");
  addAllSpot();
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
  background: linear-gradient(var(--lightGreen), var(--lightGreen));
  background-size: auto .5em;
  background-position: bottom;
  background-repeat: no-repeat;
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
  max-width: 50rem;
  margin-top: 1.5rem;
  margin-left: 7.8rem;
  border-radius: 2rem;
}

#fichier {
  width: 13rem;
}

iframe {
  width: 15rem;
  height: 10rem;
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
.placerSouvenir {
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

  .preview {
    max-width: 30rem;
  }

}
</style>