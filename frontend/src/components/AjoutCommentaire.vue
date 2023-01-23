<template>
  <div class="bg">
    <div class="add-reponse">
      <!-- <form @submit="ajoutCommentaire"> -->
        <span class="fermer" @click="$emit('fermerajoutcom')"></span>
        <div class="entete-souvenir">
          <img
            :src="(props.lAuteur.photoProfil !== null) ? param.URL_userPictures + props.lAuteur.photoProfil : '/user-invite.png'"
            :alt="props.lAuteur.nom">
          <p class="nom">{{ props.lAuteur.prenom }} {{ props.lAuteur.nom }}</p>
        </div>
        <p id="contenu-souvenir">{{ props.souvenir.textPost }}</p>
        <div class="bas-reponse">
          <img
            :src="(props.utilisateur.photoProfil !== null) ? param.URL_userPictures + props.utilisateur.photoProfil : '/user-invite.png'"
            :alt="props.utilisateur.nom">
          <textarea v-model="reponse" rows="8" cols="80" placeholder="Tapez votre réponse..." required></textarea>
        </div>
        <!-- <button type="submit">Publier</button> -->
        <button @click="ajoutCommentaire()">Publier</button>
      <!-- </form> -->
    </div>
  </div>
</template>

<script setup>
import param from "@/param/param";
import axios from 'axios'
import { ref } from 'vue';

const props = defineProps({
  souvenir: Object,
  lAuteur: Object,
  utilisateur: Object
});

const emit = defineEmits(['fermerajoutcom']);

const reponse = ref("");

function ajoutCommentaire() {
  const params = new FormData();
  params.append('typePost', 1);
  params.append('idAuteur', props.utilisateur.idUser);
  params.append('idRefPost', props.souvenir.idPost);
  params.append('textPost', reponse.value);

  //Gestion du format de la date
  var d = new Date();
  var date = d.getFullYear() + "-" + (d.getMonth() + 1).toString().padStart(2, '0') + "-" + d.getDate().toString().padStart(2, '0')
  params.append('datePost', date);

  axios.post(param.host + '/api/post/createPost.php', params).then((promise) => {
        emit('fermerajoutcom');
  });
}

</script>

<style scoped>
/*Elements graphiques*/
button {
  border: none;
  font-size: 1.8rem;
  padding: 1.5rem 4rem;
  text-align: center;
  border-radius: 4rem;
  width: 16rem;
  background-color: var(--lightGreen);
  color: #EEEDEC;
  position: absolute;
  right: 5%;
  bottom: 5%;
  cursor: pointer;
}

.nom {
  width: max-content;
  background: linear-gradient(var(--darkGreen), var(--darkGreen));
  background-size: auto .5em;
  background-position: bottom;
  background-repeat: no-repeat;
  font-weight: bold;
  margin-left: 0.5rem;
}

.fermer {
  background: url(../assets/elements-graphiques/fermer.svg) no-repeat;
  background-size: contain;
  background-position: center;
  display: block;
  width: 1.4rem;
  height: 1.4rem;
  cursor: pointer;
}

.bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(238, 237, 236, 0.4);
  z-index: 2;
  display: flex;
  justify-content: center;
  align-items: center;
}

/*Mise en pge*/
.fermer {
  margin-top: 2rem;
  margin-left: 99%;
}

.add-reponse {
  position: relative;
  background-color: #EEEDEC;
  width: 65rem;
  height: 45rem;
  border-radius: 2rem;
  padding-left: 5rem;
  padding-right: 5rem;
  z-index: 3;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

textarea {
  width: 46.5rem;
  height: 12rem;
  margin-left: 3rem;

  box-sizing: border-box;
  padding: 1.7rem 2.3rem;
  border-radius: 2rem;
  border: none;
}

.entete-souvenir {
  display: flex;
  align-items: flex-end;

}

img {
  width: 4.5rem;
  height: 4.5rem;
  border-radius: 20rem;
}

.bas-reponse {
  display: flex;
  margin-top: 8rem;
}

#contenu-souvenir {
  max-width: 72rem;
  margin-left: 5rem;
  margin-top: -1rem;
}

/* Smartphone */
@media only screen and (max-width: 480px) {
  .add-reponse {
    width: 100vw;
    padding-left: 2rem;
    padding-right: 2rem;
  }

  .fermer {
    margin-left: 97%;
  }

  button {
    margin-left: 50%;
  }
}
</style>