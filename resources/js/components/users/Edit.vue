<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Editar Usuario</h4>
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
              <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="user.name" placeholder="Usuario">
              <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
            </div>
            <div class="form-group col-md-3">
              <label>ID</label>
              <input class="form-control" type="text" v-model="user.id" readonly>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" class="form-control" :class="{'is-invalid': errors.email}" v-model="user.email" placeholder="usuario@micorreo.com">
                <div class="invalid-feedback" v-if="errors.email">{{errors.email[0]}}</div>
              </div>
              <div class="form-group">
                <label>Contraseña</label>
                <input type="password" class="form-control" :class="{'is-invalid': errors.password}" v-model="user.password">
                <div class="invalid-feedback" v-if="errors.password">{{errors.password[0]}}</div>
              </div>
              <div class="form-group">
                <label>Roles</label>
                <multiselect
                  placeholder="Seleccionar Rol"
                  selectedLabel="Seleccionado"
                  selectLabel="Presione para seleccionar"
                  deselectLabel="Presione para eliminar"
                  v-model="user.roles"
                  :options="roles"
                  :multiple="true"
                  openDirection="bottom"
                  track-by="id"
                  label="display_name"
                  :class="{'border border-danger rounded': errors.roles}">
                </multiselect>
                <small class="form-text text-danger" v-if="errors.roles">{{errors.roles[0]}}</small>
              </div>
              <div class="form-group">
                <label class="col-form-label">Registrado</label>
                <p class="form-control-plaintext text-muted">{{user.created_at | moment("LLL")}}</p>
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
      user: {},
      roles: [],
      errors: {},
      loading: true,
      submiting: false,
      submitingDestroy: false
    }
  },
  mounted () {
    this.getRoles()
    this.getUser()
  },
  methods: {
    getUser() {
      this.loading = true
      let str = window.location.pathname
      let res = str.split("/")
      axios.get(`/api/users/getUserRoles/${res[2]}`)
      .then(response => {
        this.user = response.data
      })
      .catch(error => {
        this.$toasted.global.error('El usuario no existe!')
        location.href = '/users'
      })
      .then(() => {
        this.loading = false
      })
    },
    getRoles () {
      axios.get(`/api/roles/all`)
      .then(response => {
        this.roles = response.data
      })
      .catch(error => {
        this.errors = error.response.data.errors
      })
    },
    update () {
      if (!this.submiting) {
        this.submiting = true
        axios.put(`/api/users/update/${this.user.id}`, this.user)
        .then(response => {
          this.$toasted.global.error('Usuario Actualizado!')
          location.href = '/users'
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
          text: "Una vez eliminado, no podrá recuperar este usuario.",
          icon: "warning",
          buttons: true,
          dangerMode: true,
          buttons: ["Cancelar", "OK"],
        })
        .then((willDelete) => {
          if (willDelete) {
            axios.delete(`/api/users/${this.user.id}`)
            .then(response => {
              this.$toasted.global.error('Usuario Eliminado!')
              location.href = '/users'
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
