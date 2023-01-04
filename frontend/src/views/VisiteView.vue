<template>
    <a-scene background="color: #ECECEC">
        <a-assets>
            <a-asset-item id="cityModel" src="/BatimentC.glb"></a-asset-item>
        </a-assets>
        <a-entity gltf-model="#cityModel" static-body></a-entity>

        <!-- Caméra -->
        <a-entity id="joueur" kinema-body="radius: 0.4;" movement-controls="fly: false" position="0 5 -4.531">
            <a-entity camera position="0 1.8 0" look-controls></a-entity>
        </a-entity>

    </a-scene>
</template>

<style>
a-scene {
    width: 100%;
    height: 100vh;
}
</style>
<script setup>
import { onMounted } from 'vue'

onMounted(() => {
    console.log(document.querySelector("a-entity").setAttribute("gltf-model", "#cityModel"));
    // setTimeout(() => {
    //     document.querySelector("a-entity").setAttribute("gltf-model", "#cityModel");
    // }, 1000);
})

//A-frame
// AFRAME.registerComponent('batstgi', {
//     init: function () {
//         const el = this.el
//     }
// });

</script>
