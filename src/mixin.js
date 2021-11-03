export default {

    methods: {
     toast(msg, title, variant) {
        this.$bvToast.toast(msg, {
            title: title,
            variant: variant,
            autoHideDelay: 50
        })
     }}
     
 }