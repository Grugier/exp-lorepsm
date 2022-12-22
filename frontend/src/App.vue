<template>
  <header>
    <nav class="menu">
      <ul>
        <li>
          <RouterLink to="/">Accueil</RouterLink>
        </li>
        <li>
          <RouterLink to="/visite">Visiter depuis le début</RouterLink>
        </li>
        <li>
          <RouterLink to="/about">Se connecter</RouterLink>
        </li>
      </ul>
    </nav>

    <div class="nav-toggle">
      <div class="nav-toggle-bar"></div>
    </div>

    <p id="explorepsm-logo">Exp-LorePsm</p>

    <RouterLink to="/Connexion" class="liensProfil">
      <div class="identite">
        <p>Invité</p>
        <img src="https://www.logolynx.com/images/logolynx/4b/4beebce89d681837ba2f4105ce43afac.png"
          alt="Utilisateur de LoreMMi">
      </div>
    </RouterLink>
  </header>

  <RouterView />
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';

//Si composition (+ setup)
import { onMounted } from 'vue'

onMounted(() => {

  let burger = document.querySelector('.nav-toggle');
  let lien = document.querySelectorAll('.menu li');
  let menu = document.querySelector('.menu');
  let page = document.querySelector('body');

  function doToggle() {
    menu.classList.toggle('open');
    burger.classList.toggle('active');
    page.classList.toggle('noscroll');
  }

  burger.addEventListener("click", doToggle);

  for (var i = 0; i < lien.length; i++) {
    let unLien = lien[i];
    unLien.addEventListener("click", doToggle);
  }
});
//Si option
// export default {
//   mounted() {
//     let burger = document.querySelector('.nav-toggle');
//     let lien = document.querySelectorAll('.menu li');
//     let menu = document.querySelector('.menu');
//     let page = document.querySelector('body');

//     function doToggle() {
//       menu.classList.toggle('open');
//       burger.classList.toggle('active');
//       page.classList.toggle('noscroll');
//     }

//     burger.addEventListener("click", doToggle);

//     for (var i = 0; i < lien.length; i++) {
//       let unLien = lien[i];
//       unLien.addEventListener("click", doToggle);

//     }
//   }
// }

</script>

<style scoped>

</style>
