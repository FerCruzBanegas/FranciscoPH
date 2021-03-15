<template>
  <div class="card text-white bg-danger">
    <div class="card-body">
      <div class="h1 text-muted text-right mb-4">
        <i class="icon-camera"></i>
      </div>
      <div class="text-value">{{services}}</div>
      <small class="text-muted text-uppercase font-weight-bold">Servicios registrados</small>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      services: 0
    }
  },
  mounted () {
    this.getServicesCount()
  },
  methods: {
    getServicesCount () {
      axios.get(`/api/services/count`)
      .then(response => {
        this.services = response.data
      })
    },
  }
}
</script>
<style scoped>
  .card.bg-danger {
    border-color: #19456b;
  }
  .bg-danger {
    background-color: #19456b !important;
  }
</style>