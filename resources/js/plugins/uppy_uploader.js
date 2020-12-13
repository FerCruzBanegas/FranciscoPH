import Vue from 'vue';
import UppyUploader from '../components/UppyUploader';
import CripLoading from "crip-vue-loading";
import axios from 'axios';


Vue.use(CripLoading, {axios});

new Vue({
    el: 'uppy-uploader',
    components:{
        UppyUploader
    }
});