<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Nuevo Servicio</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="confirmUpload">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <!-- <pre>{{ $data }}</pre> -->
          <div class="form-group">
            <div class="d-table-cell w-100">
              <label>Cliente</label>
              <multiselect
                placeholder="Seleccionar Cliente"
                selectedLabel="Seleccionado"
                selectLabel="Presione para seleccionar"
                deselectLabel="Presione para eliminar"
                v-model="customer.customers"
                :options="customers"
                openDirection="bottom"
                track-by="id"
                label="name"
                :class="{'border border-primary rounded': errors.customers}">
              </multiselect>
              <small class="form-text text-primary" v-if="errors.customers">{{errors.customers[0]}}</small>
            </div>
            <div class="d-table-cell align-middle">
              <button style="margin-left: 4px; margin-top: 30px;" type="button" class="btn btn-dark">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
              </button>
            </div>
          </div>
          <div class="form-group">
            <div class="card-header px-0 bg-transparent">
              <strong>Imagen</strong><br>
              <small class="text-muted">Busque y seleccione una imagen para el servicio.</small>
            </div>
            <div ref="dashboardContainer"></div>
          </div>
          <div class="form-group">
            <div class="row clearfix">
              <div class="col-md-6 col-xl-6">
                <span>Seleccione para audio</span>
                <label class="switch switch-pill switch-outline-primary-alt float-right">
                  <input class="switch-input" type="radio" value="audio" v-model="selected">
                  <span class="switch-slider"></span>
                </label>
              </div>
              <div class="col-md-6 col-xl-6">
                <span>Seleccione para video</span>
                <label class="switch switch-pill switch-outline-primary-alt float-right">
                  <input class="switch-input" type="radio" value="video" v-model="selected">
                  <span class="switch-slider"></span>
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div ref="dashboardContainer2"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import UppyUploader from '../UppyUploader';
import Uppy from '@uppy/core';
import Spanish from '@uppy/locales/lib/es_ES'
import XHRUpload from '@uppy/xhr-upload';
import Dashboard from '@uppy/dashboard';
import Form from '@uppy/form';
import notify from '../mixins/noty';
import '@uppy/core/dist/style.css';
import '@uppy/dashboard/dist/style.css';

export default {
  mixins: [notify],

  data () {
    return {
      selected: 'audio',
      customer: {},
      customers: [],
      errors: {},
      submiting: false,
      payload_img: null,
      payload_src: null,
      disabled: true,
    }
  },

  watch: {
    selected(val) {
      if (val == 'video') {
        this.uppy2.opts.restrictions.allowedFileTypes = ['.mp4', '.mpg-4', '.vlc', '.mkv']
      } else {
        this.uppy2.opts.restrictions.allowedFileTypes = ['audio/*']
      }
    }
  },

  created() {
    this.getCustomers()
  },

  mounted() {
    this.instantiateUppy()
  },

  methods: {
    instantiateUppy() {
      this.uppy = Uppy({
        debug: true,
        autoProceed: false,
        locale: Spanish,
        restrictions: {
          maxFileSize: 100000000,
          minNumberOfFiles: 1,
          maxNumberOfFiles: 1,
          allowedFileTypes: ['.jpg', '.jpeg', '.png']
        }
      })
      .use(Dashboard, {
        hideUploadButton: false,
        inline: true,
        height: 360,
        target: this.$refs.dashboardContainer,
        replaceTargetContent: true,
        showProgressDetails: true,
        browserBackButtonClose: true
      })
      .use(XHRUpload, {
        limit: 10,
        endpoint: '/file/upload',
        formData: true,
        fieldName: 'file',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // from <meta name="csrf-token" content="{{ csrf_token() }}">
        }
      });
      this.uppy.on('complete', (event) => {
        if(event.successful[0] !== undefined) {
          this.payload_img = event.successful[0].response.body.path;
          this.disabled = false;
        }
      });

      this.uppy2 = Uppy({
        debug: true,
        autoProceed: false,
        locale: Spanish,
        restrictions: {
          maxFileSize: 100000000,
          minNumberOfFiles: 1,
          maxNumberOfFiles: 1,
          allowedFileTypes: ['audio/*']
        }
      })
      .use(Dashboard, {
        hideUploadButton: false,
        inline: true,
        height: 380,
        target: this.$refs.dashboardContainer2,
        replaceTargetContent: true,
        showProgressDetails: true,
        browserBackButtonClose: true
      })
      .use(XHRUpload, {
        limit: 10,
        endpoint: '/file/upload',
        formData: true,
        fieldName: 'file',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // from <meta name="csrf-token" content="{{ csrf_token() }}">
        }
      });
      this.uppy2.on('complete', (event) => {
        if(event.successful[0] !== undefined) {
          this.payload_src = event.successful[0].response.body.path;
          this.disabled = false;
        }
      });
    },

    resetUploader() {
      this.uppy.reset();
      this.uppy2.reset();
      this.payload_img = null
      this.payload_src = null
      this.disabled = true;
      return this;
    },

    confirmUpload() {
      if(this.payload_img && this.payload_src && this.customer) {
        this.disabled = true;
        axios.post('/api/services/store', { image: this.payload_img, src: this.payload_src, type: this.selected, customer: this.customer.customers.id })
        .then(({ data }) => {
          this.notify('success', 'Se registraron los datos correctamente!');
          setTimeout(() => {location.href = `/services/${data.id}/show`}, 500);
        })
        .catch(err => {
          this.resetUploader();
        })
      } else {
        this.notify('warning', `No tienes ningún archivo en proceso`);
      }
    },

    getCustomers () {
      axios.get(`/api/customers/all`)
      .then(response => {
        this.customers = response.data
      })
      .catch(error => {
        this.errors = error.response.data.errors
      })
    },

    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`/api/customers/store`, this.customer)
        .then(response => {
          this.$toasted.global.error('Cliente Registrado!')
          location.href = '/customers'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    }
  }
}
</script>
<style scoped>
.image-container {
  height: 150px;
  width: 150px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: auto;
  margin-left: auto;
}
.image-container > img {
  width: inherit;
  height: inherit;
}
</style>