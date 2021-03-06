import Noty from 'noty';

export default {
    methods: {
        notify(type, text){
            new Noty({
                text,
                type,
                layout: 'bottomRight',
                theme: 'mint',
                timeout: 3000,
            }).show();
        }
    }
};