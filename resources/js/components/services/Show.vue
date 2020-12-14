<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-10 col-xl-10">
        <!-- <pre>{{ $data }}</pre> -->
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Datos del Servicio</h4>
          <div class="card-header-actions mr-1">
            <button class="card-header-action ml-1" @click="goBack">
              <i class="fas fa-arrow-left"></i>
              <span class="d-md-down-none ml-1">Volver a Lista</span>
            </button>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="card-header p-2 bg-tansparent">
            <strong>General</strong><br>
            <small class="text-muted">Se mostraran los datos proporcionados al momento de generar el servicio.</small>
          </div>
          <div class="row p-2">
            <div class="col-md-6 col-xl-6">
              <strong>Cliente</strong><br>
              <small class="text-muted">{{ service.customer }}</small>
            </div>
            <div class="col-md-6 col-xl-6">
              <strong>Fecha de Registro</strong><br>
              <small class="text-muted">{{ service.created | moment("LL") }}</small>
            </div>
          </div>
          <div v-if="!loading">
            <div class="row mt-2">
              <div class="col-md-6 col-xl-6">
                <div class="demo-gallery" v-viewer>
                  <div class="item-gallery">
                    <a href="#">
                      <img :src="service.image.image_url">
                    </a>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-6" style="margin-top: 3px;">
                <div class="player-container" style="height: 300px;" v-if="service.audio">
                  <vue-core-video-player :autoplay="false" :src="service.audio.audio_url"></vue-core-video-player>
                </div>
                <div class="player-container" style="height: 300px;" v-else>
                  <vue-core-video-player :autoplay="false" :src="service.video.video_url"></vue-core-video-player>
                </div>
              </div>
            </div>
            <div class="card-header p-2 bg-tansparent">
              <strong>Datos de Aplicación</strong><br>
              <small class="text-muted">Código qr que le dará acceso a los recursos desde la aplicación.</small>
            </div>
            <div class="d-flex justify-content-center p-2 bg-tansparent">
              <button @click="downloadQr" type="button" class="btn btn-primary" style="height: 40px; margin-right: 4px;">
                <i class="fas fa-download"></i>
              </button>
              <qr-code ref="qr" :text="service.qr" :size="200"></qr-code>
            </div>
          </div>
          <div class="row" v-else>
            <div class="col-md-12">
              <content-placeholders>
                <content-placeholders-text/>
              </content-placeholders>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import 'viewerjs/dist/viewer.css'

export default {
  data () {
    return {
      service: {},
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false,
      images: []
    }
  },

  mounted () {
    this.getService()
  },

  methods: {
    downloadQr(){
      let qr = this.$refs["qr"].qrCode._el.children[1].src
      let a = document.createElement('a')
      a.href = qr
      a.download = 'qr.png'
      a.click()
    },

    goBack() {
      location.href = '/services'
    },

    getService() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/services/detail/${res[2]}`)
      .then(response => {
        this.service = response.data.data
        this.images.push(this.service.image.image_url)
      })
      .catch(error => {
        this.$toasted.global.error('El servicio no existe!')
        // location.href = '/services'
      })
      .then(() => {
        this.loading = false
      })
    },

    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/customers/update/${this.customer.id}`, this.customer)
        .then(response => {
          this.$toasted.global.error('Cliente Actualizado!')
          location.href = '/customers'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },

    destroy () {
      if (!this.submitingDestroy) {
        this.submitingDestroy = true
        swal({
          title: "Estas seguro?",
          text: "Una vez eliminado, no podrá recuperar este cliente.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`/api/customers/${this.customer.id}`)
            .then(response => {
              this.$toasted.global.error('Cliente Eliminado!')
              location.href = '/customers'
            })
            .catch(error => {
              this.errors = error.response.data.errors
            })
          }
          this.submitingDestroy = false
        })
      }
    }
  }
}
</script>
<style lang="css">
.demo-gallery > div {
  margin-bottom: 15px;
  width: 100%;
  display: inline-block;
  margin-right: 10px;
  list-style: outside none none;
}

.demo-gallery > div > a {
  border: 3px solid #FFF;
  border-radius: 3px;
  display: block;
  overflow: hidden;
  position: relative;
  float: left;
}

.demo-gallery > div > a > img {
  -webkit-transition: -webkit-transform 0.15s ease 0s;
  -moz-transition: -moz-transform 0.15s ease 0s;
  -o-transition: -o-transform 0.15s ease 0s;
  transition: transform 0.15s ease 0s;
  -webkit-transform: scale3d(1, 1, 1);
  transform: scale3d(1, 1, 1);
  /*height: 100%;*/
  height: 300px;
  width: 100%;
}

.demo-gallery > div > a:hover > img {
  -webkit-transform: scale3d(1.1, 1.1, 1.1);
  transform: scale3d(1.1, 1.1, 1.1);
}

.demo-gallery > div > a:hover .demo-gallery-poster > img {
  opacity: 1;
}

.demo-gallery > div > a .demo-gallery-poster {
  background-color: rgba(0, 0, 0, 0.1);
  bottom: 0;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
  -webkit-transition: background-color 0.15s ease 0s;
  -o-transition: background-color 0.15s ease 0s;
  transition: background-color 0.15s ease 0s;
}

.demo-gallery > div > a .demo-gallery-poster > img {
  left: 50%;
  margin-left: -10px;
  margin-top: -10px;
  opacity: 0;
  position: absolute;
  top: 50%;
  -webkit-transition: opacity 0.3s ease 0s;
  -o-transition: opacity 0.3s ease 0s;
  transition: opacity 0.3s ease 0s;
}

.demo-gallery > div > a:hover .demo-gallery-poster {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>