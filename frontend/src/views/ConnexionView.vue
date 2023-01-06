<template>
    <section class="contenu">
        <div class="connexion">
            <h1>Connexion</h1>
            <p>L'accès à la connexion est réservé aux actuels et anciens étudiants de la formation PSM</p>
            <button @click="clickBouton" class="bouton-co-linkedin">Se connecter avec <span
                    id="linkedin">LinkedIn</span></button>
        </div>
    </section>
</template>

<script>
    import param from '@/param/param';

    export default {
        setup() {

            function clickBouton() {
                window.location = param.URLauthLinkedIn;
            }

            // expose to template and other options API hooks
            return {
                clickBouton
            }
        },
    }

</script>

<style scoped>
/*Boutons styles graphiques*/
button {
    border: none;
    font-size: 1.8rem;
    padding: 1.6rem 6rem;
    text-align: center;
    border-radius: 4rem;
    width: 39rem;
    margin-top: 2rem;
}

h1 {
    margin-top: 7rem;
}

.bouton-co-linkedin {
    background-color: #78C1F4;
    color: #EEEDEC;
    cursor: pointer;
}

#linkedin {
    background: url(../assets/elements-graphiques/linkedin-logo.svg) no-repeat;
    background-size: contain;
    background-position: center;
    width: 8rem;
    height: 2rem;
    color: rgba(0, 0, 0, 0);
}

p {
    color: #756464;
    text-align: center;
    width: 40rem;
}

/*Mise en page bloc de connexion*/
button:first-of-type {
    margin-bottom: 1rem;
}

.contenu {
    display: flex;
    justify-content: center;
    align-items: center;
}

.connexion {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 15rem;
}

/*Responsive*/
@media only screen and (max-width: 480px) {

    /*Smartphone en mode portrait*/
    p {
        width: auto;
    }

    .bouton-co-linkedin {
        width: 32rem;
        padding: 1.6rem 2rem;
    }
}

@media only screen and (min-width: 62em) {

    /*Ordinateur*/
    /*Animations*/
    /* .connexion {
        animation: entreeSlideHaut 1s;
    } */
}

</style>
