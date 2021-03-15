<template>
  <div class="container">
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2">Lista de Servicios</h4>
      <div class="card-header-actions mr-1">
        <a class="btn btn-primary" href="/services/create">
          <i class="btn-addon-icon fa fa-plus-circle" /> Nuevo Servicio
        </a>
      </div>
    </div>
    <div class="card-body px-0">
      <div class="row justify-content-between">
        <div class="col-7 col-md-5">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" @click="filter">
                <i class="fas fa-search"></i>
              </span>
            </div>
            <input type="text" class="form-control" placeholder="Buscar" v-model.trim="filters.search" @keyup.enter="filter">
          </div>
        </div>
        <div class="col-auto">
          <multiselect
            v-model="filters.pagination.per_page"
            :options="[25,50,100,200]"
            :searchable="false"
            :show-labels="false"
            :allow-empty="false"
            @select="changeSize"
            placeholder="Search">
          </multiselect>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th class="d-none d-sm-table-cell">
                <a href="#" class="text-dark" @click.prevent="sort('id')">ID</a>
                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'id' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'id' && filters.orderBy.direction == 'desc'}"></i>
              </th>
              <th class="d-none d-sm-table-cell">
                <a href="#" class="text-dark" @click.prevent="sort('date')">Fecha</a>
                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'date' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'date' && filters.orderBy.direction == 'desc'}"></i>
              </th>
              <th>Cliente</th>
              <th class="d-none d-sm-table-cell">
                <a href="#" class="text-dark" @click.prevent="sort('created_at')">Registrado</a>
                <i class="mr-1 fas" :class="{'fa-long-arrow-alt-down': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'asc', 'fa-long-arrow-alt-up': filters.orderBy.column == 'created_at' && filters.orderBy.direction == 'desc'}"></i>
              </th>
              <th class="d-none d-sm-table-cell"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="service in services" >
              <td class="d-none d-sm-table-cell">{{service.id}}</td>
              <td>
                {{ service.date | moment("DD/MM/YYYY") }}
              </td>
              <td>
                {{ service.customer }}
              </td>
              <td class="d-none d-sm-table-cell">
                <small>{{service.created_at | moment("LL") }}</small> - <small class="text-muted">{{service.created_at | moment("LT") }}</small>
              </td>
              <td class="d-sm-table-cell">
                <button @click="showService(service.id)" class="text-muted"><i class="fas fa-eye"></i></button>
                <!-- <button @click="destroy"class="btn btn-outline-danger btn-sm">
                  <i class="fas fa-spinner fa-spin" v-if="submitingDestroy"></i>
                  <i class="fas fa-trash" v-else></i>
                </button> -->
                <!-- <button @click="editService(service.id)" class="text-muted"><i class="fas fa-pencil-alt"></i></button> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="row" v-if='!loading && filters.pagination.total > 0'>
        <div class="col pt-2">
          {{filters.pagination.from}}-{{filters.pagination.to}} De {{filters.pagination.total}}
        </div>
        <div class="col" v-if="filters.pagination.last_page>1">
          <nav aria-label="Page navigation">
            <ul class="pagination justify-content-end">
              <li class="page-item" :class="{'disabled': filters.pagination.current_page <= 1}">
                <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page -  1)"><i class="fas fa-angle-left"></i></a>
              </li>
              <li class="page-item" v-for="page in filters.pagination.last_page" :class="{'active': filters.pagination.current_page == page}">
                <span class="page-link" v-if="filters.pagination.current_page == page">{{page}}</span>
                <a class="page-link" href="#" v-else @click.prevent="changePage(page)">{{page}}</a>
              </li>
              <li class="page-item" :class="{'disabled': filters.pagination.current_page >= filters.pagination.last_page}">
                <a class="page-link" href="#" @click.prevent="changePage(filters.pagination.current_page +  1)"><i class="fas fa-angle-right"></i></a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      <div class="no-items-found text-center mt-5" v-if="!loading && !services.length > 0">
        <i class="icon-magnifier fa-3x text-muted"></i>
        <p class="mb-0 mt-3"><strong>No se pudo encontrar ningún registro</strong></p>
        <p class="text-muted">Intente cambiar los filtros o agregar uno nuevo</p>
        <a class="btn btn-primary" href="/services/create" role="button">
          <i class="fa fa-plus"></i>&nbsp; Nuevo Servicio
        </a>
      </div>
      <content-placeholders v-if="loading">
        <content-placeholders-text/>
      </content-placeholders>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      services: [],
      filters: {
        pagination: {
          from: 0,
          to: 0,
          total: 0,
          per_page: 25,
          current_page: 1,
          last_page: 0
        },
        orderBy: {
          column: 'id',
          direction: 'desc'
        },
        search: ''
      },
      loading: true,
      submitingDestroy: false
    }
  },
  mounted () {
    if (localStorage.getItem("filtersTableServices")) {
      this.filters = JSON.parse(localStorage.getItem("filtersTableServices"))
    } else {
      localStorage.setItem("filtersTableServices", this.filters);
    }
    this.getServices()
  },
  methods: {
    getServices () {
      this.loading = true
      this.services = []

      localStorage.setItem("filtersTableServices", JSON.stringify(this.filters));

      axios.post(`/api/services/filter?page=${this.filters.pagination.current_page}`, this.filters)
      .then(response => {
        this.services = response.data.data
        delete response.data.data
        this.filters.pagination = response.data.meta
        this.loading = false
      })
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
    },

    showService (serviceId) {
      location.href = `/services/${serviceId}/show`
    },

    editService (serviceId) {
      location.href = `/services/${serviceId}/edit`
    },

    filter() {
      this.filters.pagination.current_page = 1
      this.getServices()
    },

    changeSize (perPage) {
      this.filters.pagination.current_page = 1
      this.filters.pagination.per_page = perPage
      this.getServices()
    },

    sort (column) {
      if(column == this.filters.orderBy.column) {
        this.filters.orderBy.direction = this.filters.orderBy.direction == 'asc' ? 'desc' : 'asc'
      } else {
        this.filters.orderBy.column = column
        this.filters.orderBy.direction = 'asc'
      }

      this.getServices()
    },

    changePage (page) {
      this.filters.pagination.current_page = page
      this.getServices()
    }
  }
}
</script>
