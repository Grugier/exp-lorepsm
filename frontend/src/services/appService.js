import param from '@/param/param'

export default {
    //Lecture
    getLocal(){
        return JSON.parse(localStorage.getItem(param.localStore));
    },
    //Maj
    setLocal(value){
        localStorage.setItem(param.localStore, JSON.stringify(value));
    },
    //Suppression
    removeLocal(value){
        localStorage.removeItem(param.localStore);
    },
}