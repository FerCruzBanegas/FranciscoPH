<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-md-9 col-xl-7">
        <div class="card-header px-0 mt-2 bg-transparent clearfix">
          <h4 class="float-left pt-2">Nuevo Usuario</h4>
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
            <input type="text" class="form-control" :class="{'is-invalid': errors.name}" v-model="user.name" placeholder="usuario">
            <div class="invalid-feedback" v-if="errors.name">{{errors.name[0]}}</div>
          </div>
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
              :class="{'border border-primary rounded': errors.roles}">
            </multiselect>
            <small class="form-text text-primary" v-if="errors.roles">{{errors.roles[0]}}</small>
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
      user: {
        roles: []
      },
      roles: [],
      errors: {},
      submiting: false
    }
  },
  mounted () {
    this.getRoles()
  },
  methods: {
    create () {
      if (!this.submiting) {
        this.submiting = true
        axios.post(`/api/users/store`, this.user)
        .then(response => {
          this.$toasted.global.error('Usuario Registrado!')
          location.href = '/users'
        })
        .catch(error => {
          this.errors = error.response.data.errors
          this.submiting = false
        })
      }
    },
    getRoles () {
      axios.get(`/api/roles/all`)
      .then(response => {
        this.roles = response.data
      })
      .catch(error => {
        this.errors = error.response.data.errors
      })
    }
  }
}
</script>
