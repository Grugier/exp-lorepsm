<template>
  <main>
    <section id="accueil">
      <div id="accueil-gauche">
        <div class="flex-center">
          <div>
            <h1 id="explorepsm">Exp-lorePSM</h1>
            <router-link to="/Visite" id="visite"><span>Visitez avec nous</span></router-link>
          </div>
        </div>
      </div>
      <div id="accueil-droite">
        <span id="model-stgi">Bâtiment STGI C</span>
      </div>
    </section>

    <section id="presentation">
      <div>
        <h2>Exploration</h2>
        <p>virtuelle du siège de la formation PSM à Montbéliard</p>
      </div>
      <div>
        <h2>Expression</h2>
        <p>de souvenirs entre les anciens et actuels étudiants</p>
      </div>
      <div>
        <h2>Explication</h2>
        <p>des connaissances et des compétences mobilisées dans les formations</p>
      </div>
    </section>

    <footer>
      <div id="footer-liens">
        <div>
          <router-link to="/about">À propos</router-link>
          <a href="mailto:contact@loremmi.fr">Nous contacter</a>
          <a href="/mentions-legales.pdf" target="_blank">Mentions légales</a>
        </div>
        <div></div>
      </div>
      <div id="footer-rassurance">
        <div>
          <a href="https://www.ubfc.fr/" class="ubfc">Université de bourgogne Franche-comté</a>
          <a href="https://stgi.univ-fcomte.fr/" class="ufrstgi">UFR STGI</a>
          <a href="https://psm-montbeliard.fr/" class="logo-psm">Produit et Services Multimédia</a>
          <a href="https://www.univ-fcomte.fr/" class="ufc">Université de Franche Comté</a>
        </div>
        <p>Projet réalisé dans le cadre du projet de fin d'étude du Master PSM de Montbéliard - 2023 Exp-LorePSM : Casperot, Truchi</p>
      </div>
    </footer>
  </main>
</template>

<script setup>

</script>

<style scoped>
/*===Styles graphiques===*/
#explorepsm {
  background: url(../assets/logo.svg) no-repeat;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  width: 75rem;
  height: 15rem;
}

/*Flèche a coté de visitez avec nous*/
#visite:after {
  display: inline-block;
  content: "";
  width: 1.2rem;
  height: 1.2rem;
  background: url(../assets/landing-page/fleche.svg) no-repeat;
  margin-left: 1.2rem;
  margin-bottom: 0.2rem;
}

#model-stgi {
  background: url(../assets/landing-page/stgic.png) no-repeat center;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  display: block;
  height: 85vh;
  width: 22.8vw;
}

/*Partie présentation*/
#presentation {
  background-color: var(--darkGreen);
}

#presentation p {
  color: #EEEDEC;
}

/*Logos du pied de page*/
.ubfc {
  display: block;
  background: url(../assets/landing-page/logos/ubfc-logo.png) no-repeat;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  width: 20.9rem;
  height: 9.3rem;
  background-position: center;
}

.ufrstgi {
  display: block;
  background: url(../assets/landing-page/logos/stgi-logo.png) no-repeat;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  width: 20.9rem;
  height: 9.3rem;
  background-position: center;
}

.logo-psm {
  display: block;
  background: url(../assets/landing-page/logos/psm-logo.png) no-repeat;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  width: 20.9rem;
  height: 9.3rem;
  background-position: center;
}

.ufc {
  display: block;
  background: url(../assets/landing-page/logos/ufc-logo.png) no-repeat;
  background-size: contain;
  text-indent: 100%;
  white-space: nowrap;
  overflow: hidden;
  width: 20.9rem;
  height: 9.3rem;
  background-position: center;
}

/*Soulignement*/
#visite>span {
  width: max-content;
  background: linear-gradient(var(--lightGreen), var(--lightGreen));
  background-size: auto .5em;
  background-position: bottom;
  background-repeat: no-repeat;
}

/*Barre de séparation du milieu*/
#accueil-droite {
  border-left: 2px solid #302A2A;
}

/*===Style mise en page===*/
/*Eviter la barre de scroll*/
main {
  overflow: hidden;
}

/*Première section*/
#accueil {
  width: 100vw;
  height: 100vh;
  display: flex;
}

#accueil-gauche {
  animation: entreeSlideGauche 2s;
  width: 50vw;
}

#accueil-droite {
  display: flex;
  align-items: center;
  justify-content: center;
  animation: entreeSlideBas 2.05s;
  width: 50vw;
}

.flex-center {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 90.1vh;
}

/*Partie présentation*/
#presentation {
  padding-top: 10vh;
  padding-bottom: 10vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

#presentation div {
  width: 20.7%;
  height: 18.7%;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  text-align: center;
}

#presentation h2 {
  margin-bottom: 0;
  color: #EEEDEC;
}

#presentation p {
  font-size: 2.4rem;
}

#presentation>div:nth-child(2),
#presentation>div:last-of-type {
  margin-top: 5vh;
}

/*Footer*/
footer {
  padding: 10vh 10vw 5vh 10vw;
}

#footer-rassurance {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin-top: 10vh;
}

#footer-rassurance>div {
  display: flex;
  width: 56vw;
  justify-content: space-around;
  align-items: center;
  margin-bottom: 2rem;
}

#footer-liens {
  display: flex;
  flex-direction: row;
}

#footer-liens>div:first-of-type {
  display: flex;
  flex-direction: column;
  margin-top: 4vh;
}

#footer-liens>div:first-of-type a {
  margin-bottom: 1.6rem;
}

/*===Animations===*/
/*Liens du footer*/
#footer-liens a:hover {
  transform: translateX(13rem);
  color: var(--lightGreen);
}

#footer-liens a {
  transition: color .4s, transform .8s;
}


/*===Responsive smartphone===*/
@media only screen and (max-width: 480px) {

  #footer-liens>div,
  #explorepsm {
    display: none;
  }

  #visite {
    position: absolute;
    bottom: 4rem;
    left: 15%;
    z-index: 1;
    animation: entreeSlideGauche 2s;
  }

  #accueil-droite {
    border: none;
    animation: entreeSlideDroit 2s;
  }

  #presentation>div:first-of-type,
  #presentation>div:nth-child(2),
  #presentation>div:last-of-type {
    margin-left: 3rem;
    padding-right: 3rem;
    width: auto;
  }

  #model-stgi {
    background: url(../assets/landing-page/stgic.png) no-repeat center;
    background-size: contain;
    text-indent: 100%;
    white-space: nowrap;
    overflow: hidden;
    display: block;
    width: 100vw;
    position: absolute;
    top: 45px;
    left: 0;
  }

  #presentation h2 {
    font-size: 4rem;
  }

  #presentation div:before {
    margin-bottom: -2rem;
  }

  p {
    font-size: 2rem;
  }

  #footer-liens a {
    font-size: 3.6rem;
  }

  footer p {
    text-align: center;
  }

  #footer-rassurance>div {
    flex-direction: column;
  }

  #footer-rassurance>div a {
    margin-bottom: 5rem;
  }

  #accueil-droite,
  #accueil-gauche {
    animation: none;
  }

  #footer-liens a:hover {
    transform: none;
    color: #302A2A;
  }
}
</style>
