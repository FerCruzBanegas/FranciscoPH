<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Nuevo Cliente</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="create">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="customer.name">
            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
          </div>
          <div class="form-group">
            <div class="row justify-content-md-center">
              <div class="col-md-6 col-sm-12">
                <label>Teléfono</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="customer.phone">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
              </div>
              <div class="col-md-6 col-sm-12">
                <label>CI</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.ci}" v-model="customer.ci">
                <div class="invalid-feedback" v-if="errors.ci">{{errors.ci[0]}}</div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Correo</label>
            <input type="text" class="form-control" :class="{'is-invalid': errors.email}" v-model="customer.email">
            <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
          </div>
          <div class="form-group">
            <label>Dirección</label>
            <textarea rows="3" class="form-control" :class="{'is-invalid': errors.address}" v-model="customer.address">
            </textarea>
            <div class="invalid-feedback" v-if="errors.address">{{errors.address[0]}}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      customer: {},
      roles: [],
      errors: {},
      submiting: false
    }
  },

  methods: {
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
