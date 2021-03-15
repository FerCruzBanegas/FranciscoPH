<template>
  <div>
    <div class="card-header px-0 mt-2 bg-transparent clearfix">
      <h4 class="float-left pt-2 font-weight-bold">Datos del Servidor</h4>
      <div class="card-header-actions mr-1">
        <i class="fas fa-server fa-3x" style="color: #0d5cab;"></i>
      </div>
    </div>
    <div class="card-body px-0">
      <section class="cardRows">
        <div class="card">
          <div class="card__groups">
            <div class="card__group">
              <h3 class="card__title">Total <div class="aka">Espacio en disco</div></h3>
              <!-- <div class="card__subtitle">Subtitle</div> -->
              <div class="percent">
                {{ usage.total.percentage }}%
              </div>
            </div>
            <div class="card__group">
              <div class="fraction-total">
                {{ usage.total.value }}
                <div class="fraction__label">GB</div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card__groups">
            <div class="card__group">
              <h3 class="card__title">Usado <div class="aka">Espacio en disco</div></h3>
              <!-- <div class="card__subtitle">Subtitle</div> -->
              <div class="percent">
                {{ usage.used.percentage }}%
              </div>
            </div>
            <div class="card__group">
              <div class="fraction-used">
                {{ usage.used.value }}
                <div class="fraction__label">GB</div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card__groups">
            <div class="card__group">
              <h3 class="card__title">Disponible <div class="aka">Espacio en disco</div></h3>
              <!-- <div class="card__subtitle">Subtitle</div> -->
              <div class="percent">
                {{ usage.free.percentage }}%
              </div>
            </div>
            <div class="card__group">
              <div class="fraction-available">
                {{ usage.free.value }}
                <div class="fraction__label">GB</div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      usage: null
    }
  },
  mounted () {
    this.getDiskUsage()
  },
  methods: {
    getDiskUsage () {
      axios.get(`/disk_usage`)
      .then(response => {
        this.usage = response.data
      })
    },
  }
}
</script>
<style scoped lang="scss">
.cardRows {
  font: 300 16px "Open Sans";
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.card {
  background: white;
  padding: 1em;
  margin: 1em;
  border-radius: .2em;
  box-shadow: 0 0 .3em rgba(0,0,0,0.3);
  flex: 1 0 250px;
  display: flex;
  flex-wrap: wrap;
  flex-direction: column;
  justify-content: space-between;
  
  &__groups {
    display: flex;
    justify-content: space-between;
    
  }
  
  @media (min-width: 650px) {
    max-width: 50%;
  }
  
  &__group {
    &:first-child {
      margin-right: 1em;
    }   
  }
  
  &__title {
    margin-top: 0;
    .aka {
      font-weight: 400;
      font-size: 0.8rem;
      color: #888;
    }
  }
  
  &__subtitle {
    font-weight: 400;
  }
}

.bottomLinks a {
  color: #147ea7;
  text-decoration: none;
  font-size: .8rem;
  
  &:hover {
    text-decoration: underline;
  }
}

.fraction-total {
  background: #045762;
  border-radius: 50%;
  font-size: 1rem;
  color: #fff;
  width: 6em;
  padding: 1rem;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  
  &__label {
    font-size: .75rem;
    font-weight: 300;
    line-height: 1.4rem;
  }
  
  hr {
    margin: .25rem 0 .5em;
  }
}

.fraction-available {
  background: #4e8d7c;
  border-radius: 50%;
  font-size: 1rem;
  color: #fff;
  width: 6em;
  padding: 1rem;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  
  &__label {
    font-size: .75rem;
    font-weight: 300;
    line-height: 1.4rem;
  }
  
  hr {
    margin: .25rem 0 .5em;
  }
}

.fraction-used {
  background: #ea97ad;
  border-radius: 50%;
  font-size: 1rem;
  color: #fff;
  width: 6em;
  padding: 1rem;
  text-align: center;
  line-height: 2em;
  font-weight: 700;
  
  &__label {
    font-size: .75rem;
    font-weight: 300;
    line-height: 1.4rem;
  }
  
  hr {
    margin: .25rem 0 .5em;
  }
}

.percent {
  font-size: 2rem;
  font-weight: 700;
}
</style>