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
          <RouterLink to="/connexion">Se connecter</RouterLink>
        </li>
      </ul>
    </nav>

    <div class="nav-toggle">
      <div class="nav-toggle-bar"></div>
    </div>

    <RouterLink to="/" id="explorepsm-logo">Exp-LorePsm</RouterLink>

    <RouterLink to="/Profil" class="liensProfil" v-if="store.userCo.idUser != 0">
      <div class="identite">
        <p>{{ store.userCo.prenom }}</p>
        <img :src="store.userCo.img" alt="Utilisateur de LoreMMi">
      </div>
    </RouterLink>

    <RouterLink to="/Connexion" class="liensProfil" v-else>
      <div class="identite">
        <p>Invité</p>
        <img src="https://www.logolynx.com/images/logolynx/4b/4beebce89d681837ba2f4105ce43afac.png"
          alt="Utilisateur de LoreMMi">
      </div>
    </RouterLink>

  </header>

  <RouterView />
</template>

<script>
import { RouterLink, RouterView } from 'vue-router';
import { onMounted } from 'vue'

import param from "@/param/param";
import appService from '@/services/appService'

import { useExploreStore } from "@/stores/exploreStore";
import { storeToRefs } from 'pinia';
import { useRouter } from "vue-router";
import axios from "axios";

export default {
  setup() {

    // On crée une instance du store de l'application
    const store = useExploreStore();
    const { userCo } = storeToRefs(store);
    const router = useRouter();

    // Navigation guards
    router.beforeEach(to => {

      // SI MESSAGE D'ERREUR
      if (to.query.err) {
        let messageErreur;
        switch (to.query.err) {
          case "err-li":
            messageErreur = "Il y a eu un problème lors de la connexion avec votre compte LinkedIn.";
            break;
          default:
            messageErreur = "Une erreur s'est produite.";
        }
        alert(messageErreur);
      }

      // CONNEXION AVEC LINKEDIN
      else if (to.query.prog && to.query.prog !== '') {
        // Base64Decode
        const prog = decodeURIComponent(escape(window.atob(to.query.prog)));
        fetchUserInfos(prog, to);
      }

    });

    function resetStoreUserInfo() {
      store.userCo.idUser = 0;
      store.userCo.typeUser = 0;
      store.userCo.prenom = "";
      store.userCo.nom = "";
      store.userCo.photoProfil = null;
      store.userCo.promo = 0;
    }

    // Si on vient de se connecter (première connexion ou rafraîchissement de token)
    // on a un paramètre dans l'URL (ou dans le local storage) permettant de retrouver l'utilisateur connecté
    function fetchUserInfos(prog, path) {

      const params = new FormData;
      params.append('promo', prog);

      axios.post(param.URL_getLoggedUser, params).then(response => {

        if (response.data.idUser) {

          const logged = response.data;

          store.userCo.idUser = logged.idUser;
          store.userCo.typeUser = logged.typeUser;
          store.userCo.prenom = logged.prenom;
          store.userCo.nom = logged.nom;
          store.userCo.photoProfil = logged.photoProfil;
          store.userCo.promo = logged.annee;

          let userLocalStorage = {
            promo: response.data.promo,
            progression: response.data.progression,
          };

          // On met cela dans le localStorage
          appService.setLocal(userLocalStorage);
        } else {
          alert("Une erreur s'est produite.");
        }

        // On enlève la query de l'URL
        if (path !== null) {
          router.replace(path.path);
        }

      });

    }


    function checkLocalStorage() {

      // Initialisation des informations depuis le localStorage
      const localStorage = appService.getLocal();

      // S'il y a déjà un localStore, on le lit
      if (localStorage != null) {

        // Si il semble valide
        if (localStorage.promo != null && localStorage.progression != null) {

          ///////////////////
          // UTILISATEURS /////////////////////////////////////////////////////////////////////////////////////////////
          //////////////////

          // On vérifie que la chaîne est correcte
          let t = localStorage.promo;
          if (t.includes('=::')) {
            const lIdLinkedIn = localStorage.promo;
            const token = localStorage.progression;

            // On cherche donc à savoir si l'utilisateur existe bel et bien
            const params = new FormData;
            params.append('idLIStor', lIdLinkedIn);
            params.append('tokenLIStor', token);

            axios.post(param.URL_loginFromFrontend, params).then(response => {

              const rep = response.data;

              // On a plusieurs cas possibles suivant les informations
              // stockées dans le localStorage

              // Si erreur ou que l'utilisateur n'est pas valide (informations erronées)
              // On vide le localStorage
              if (rep.includes("err--") || rep === "user--NOT-OK") {

                // Problème lors du décryptage -> localStorage modifié, on le vide de toute façon
                appService.removeLocal();
                resetStoreUserInfo();
                router.replace("/");
              }

              // On regarde les autres cas
              switch (rep) {

                case "token--refresh":
                  // L'utilisateur existe et s'est déjà connecté
                  // Cependant son token devrait être rafraîchi
                  // On vide son localStorage car il en obtiendra
                  // un autre s'il rafraîchit son token
                  appService.removeLocal();
                  resetStoreUserInfo();

                  // On le redirige ensuite vers la page de connexion
                  router.replace("/Connexion");
                  break;
                case "token--ok":
                  // L'utilisateur existe et s'est déjà connecté
                  // Son token est encore valide, on cherche alors ses informations
                  fetchUserInfos(localStorage.promo, null);
                  break;
              }

            });

          }

          else {
            appService.removeLocal();
            resetStoreUserInfo();
            router.replace("/");
          }

        }

        // Si c'est un localStorage inutilisable, on le vide
        else {
          appService.removeLocal();
          resetStoreUserInfo();
          router.replace("/Connexion");
        }

      }

      // Sinon, on ne fait rien car l'utilisateur n'est pas connecté (visiteur)
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    checkLocalStorage();

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
    return {
      userCo 
    }
  },
}

</script>

<style scoped>

</style>
