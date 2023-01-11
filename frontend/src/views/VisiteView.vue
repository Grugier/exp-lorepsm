<template>
    <a-scene raycaster="objects: .clickable" cursor="rayOrigin: mouse" renderer="colorManagement: true;"
        background="color: #ECECEC">
        <!-- <a-assets>
            <a-asset-item id="stgic" src="/BatimentC.glb"></a-asset-item>
        </a-assets>
        <a-entity static-body></a-entity> -->

        <!-- ENVIRONNEMENT DE TEST EN ATTENDANT LE BATIMENT -->

        <!-- Plafond -->
        <a-plane static-body position="-0.62674 6.967 -4.46685" rotation="90 0 0" width="35.99" height="36.95"
            color="#ffffff" material="shader: flat;"></a-plane>

        <!-- Sol -->
        <a-plane static-body position="-0.62674 0 -4.46685" rotation="-90 0 0" width="35.99" height="36.95"
            color="#999999" shadow></a-plane>

        <!-- Murs -->
        <a-box static-body position="16.61 3.5 -4.57041" geometry="height: 8; width: 37.27" rotation="0 90 0"></a-box>
        <a-box static-body position="-17.857 3.5 -4.35469" geometry="height: 8; width: 37.19" rotation="0 90 0"></a-box>
        <a-box static-body position="-0.43974 3.5 7.604" geometry="height: 8; width: 35.52"></a-box>
        <a-box static-body position="-0.4882 3.5 -22.5" geometry="height: 8; width: 35.59"></a-box>

        <a-box static-body position="7 .7 0" geometry="height: 2; width: 2"></a-box>

        <a-entity light="type: ambient; intensity: 0.35;"></a-entity>
        <a-entity light="type: ambient; intensity: 0.35;"></a-entity>
        <a-entity light="type: directional;
                             castShadow: true;
                             intensity: 0.25;" position="-5 3 1.5"></a-entity>

        <!-- FIN ENVIRONNEMENT DE TEST -->

        <!-- Caméra -->
        <a-entity id="joueur" kinema-body="radius: 0.4;" movement-controls="fly: false" position="0 0 -4.531">
            <a-entity camera position="0 1.8 0" look-controls></a-entity>
        </a-entity>

        <!-- Souvenir statique de test -->
        <!-- <a-entity souvenir id="sphere" geometry="primitive: sphere; radius: 0.25"
                material="color: #99BF1C; shader: flat"
                position="7 2 0"
                light="type: point; intensity: 0.075"
                class="clickable"
              ></a-entity> -->
    </a-scene>

    <Souvenir v-show="souvenir.open" @fermersouvenir="souvenir.open = false" :idSouvenir="souvenir.idClicked" />
</template>

<style>
a-scene {
    width: 100%;
    height: 100vh;
}
</style>
<script setup>
import { reactive, onMounted } from 'vue'
import axios from 'axios'
import Souvenir from '../components/Souvenir.vue'

let souvenir = reactive({ open: false, idClicked : null })
//let lstSouvenirs = {};

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
    entityEl.setAttribute('position', {x: coords[0], y: coords[1], z: coords[2]});
    entityEl.setAttribute('class', 'clickable');
    entityEl.setAttribute('souvenir', '');
    entityEl.setAttribute('id', spot.idPost);

    sceneEl.appendChild(entityEl);
}

onMounted(() => {
    //Pour charger un modèle
    //document.querySelector("a-entity").setAttribute("gltf-model", "#stgic");

    //Charger la liste des souvenirs
    axios.get('http://localhost/exp-lorepsm/backend/api/post/getPostsList.php').then((list) => {
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
