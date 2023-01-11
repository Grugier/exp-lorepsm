<template>
    <section class="contenu">
        <div>
            <h1>Profil</h1>
            <div class="profil">
                <div class="profilEnTete flex">
                    <img :src="'http://localhost/exp-lorepsm/backend/documentsUGC/profilePicUsers/' + userCo.photoProfil"
                        :alt="userCo.nom">
                    <div class="infosUtilisateur">
                        <h2>{{ userCo.prenom }} {{ userCo.nom }}</h2>
                        <p>Promo {{ userCo.promo }}</p>
                        <div class="deco" @click="deconnexion">
                            <span class="deconnexion"></span>
                            <p>Déconnexion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { useExploreStore } from "@/stores/exploreStore";
import { storeToRefs } from 'pinia';

export default {
    setup() {
        const store = useExploreStore();
        const { userCo } = storeToRefs(store);

        return {
            userCo
        }
    },

}
</script>

<style scoped>
/*Elements graphiques*/
.deconnexion {
    background: url(../assets/elements-graphiques/deconnexion.svg) no-repeat;
    background-size: contain;
    background-position: center;
    height: 4rem;
    width: 4rem;
    display: block;
    margin-right: 1rem;
}

/*Typos*/
.infosUtilisateur p {
    font-size: 2.4rem;
    font-weight: bold;
    margin-top: 0.9rem;
    margin-bottom: 3rem;
}

.infosUtilisateur h2 {
    margin: 0;
}

a {
    font-size: 2.4rem;
}

h2 {
    font-size: 3rem;
}

/*Mise en page du bloc profil*/
.profil {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 7rem;
}

.profilEnTete>img {
    width: 15.3rem;
    height: 15.3rem;
    margin-right: 5rem;
    border-radius: 20rem;
}

.listeSouvenirs {
    display: flex;
    flex-direction: column;
    align-items: center;
}

h4 {
    margin-right: 14rem;
}

h3 {
    margin-bottom: 0;
}

.deco {
    display: flex;
    cursor: pointer;
    width: fit-content;
}

.deco p {
    margin-bottom: 0;
    background: linear-gradient(var(--darkGreen), var(--darkGreen));
    background-size: auto .5em;
    background-position: bottom;
    background-repeat: no-repeat;
}

/*Mise en page liste souvenirs*/
#souvenirs {
    width: 50rem;
}

h1 {
    margin-top: 12rem;
    margin-bottom: 5rem;
    text-align: center;
}

p {
    color: #756464;
    max-width: 70rem;
}

.contenu {
    display: flex;
    justify-content: center;
    align-items: center;
}

/*Responsive*/
/* Smartphone */
@media only screen and (max-width: 480px) {

    .forme-prog,
    .vague,
    .carre-lune {
        display: none;
    }

    h1 {
        display: none;
    }

    h2 {
        font-size: 2.4rem;
    }

    h3 {
        margin-top: 6rem;
    }

    .infosUtilisateur p {
        font-size: 1.8rem;
    }

    .profilEnTete>img {
        width: 5.8rem;
        height: 5.8rem;
    }

    .deconnexion {
        width: 2rem;
        height: 2rem;
    }

    .deco {
        align-items: center;
    }

    #souvenirs {
        width: auto;
    }

    .listeSouvenirs:before {
        content: none;
    }
}
</style>
