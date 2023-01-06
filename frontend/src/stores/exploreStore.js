import { ref, computed } from 'vue'
import { defineStore } from 'pinia'

export const useExploreStore = defineStore({
  id: 'explore',
  state: () => {
    return {
      userCo: {
        idUser: 0,
        typeUser: 0,
        prenom: "",
        nom: "",
        photoProfil: null,
        promo: 0
      }
    }

  },

});
