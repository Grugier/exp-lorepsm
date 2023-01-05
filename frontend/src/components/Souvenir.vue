<template>
    <div>
        <p @click="$emit('fermersouvenir')" class="close">X</p>
        <p>Bonjour je suis un souvenir lol</p>
    </div>
</template>

<script setup>

</script>

<style scoped>
div {
    background-color: blueviolet;
    width: 17rem;
    height: 12rem;
    position: absolute;
    top: 20%;
    left: 20%;
}
.close {
    cursor: pointer;
}
</style>
