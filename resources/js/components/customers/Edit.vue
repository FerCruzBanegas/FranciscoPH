<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar Cliente</h4>
          <div class="card-header-actions mr-1">
            <a class="btn btn-primary" href="#" :disabled="submiting" @click.prevent="update">
              <i class="fas fa-spinner fa-spin" v-if="submiting"></i>
              <i class="fas fa-check" v-else></i>
              <span class="ml-1">Guardar</span>
            </a>
            <a class="card-header-action ml-1" href="#" :disabled="submitingDestroy" @click.prevent="destroy">
              <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
              <i class="far fa-trash-alt" v-else></i>
              <span class="d-md-down-none ml-1">Eliminar</span>
            </a>
          </div>
        </div>
        <div class="card-body px-0">
          <div class="row" v-if="!loading">
            <div class="form-group col-md-9">
              <label>Nombre</label>
              <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="customer.name">
              <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-3">
              <label>ID</label>
              <input class="form-control" type="text" v-model="customer.id" readonly>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Teléfono</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.phone}" v-model="customer.phone">
                <div class="invalid-feedback" v-if="errors.phone">{{errors.phone[0]}}</div>
              </div>
              <div class="form-group">
                <label>CI</label>
                <input type="text" class="form-control" :class="{'is-invalid': errors.ci}" v-model="customer.ci">
                <div class="invalid-feedback" v-if="errors.ci">{{errors.ci[0]}}</div>
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
              <div class="form-group">
                <label class="col-form-label">Registrado</label>
                <p class="form-control-plaintext text-muted">{{customer.created_at | moment("LLL")}}</p>
              </div>
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
export default {
  data () {
    return {
      customer: {},
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },

  mounted () {
    this.getCustomer()
  },

  methods: {
    getCustomer() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/customers/${res[2]}`)
      .then(response => {
        this.customer = response.data
      })
      .catch(error => {
        this.$toasted.global.error('El cliente no existe!')
        location.href = '/customers'
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
