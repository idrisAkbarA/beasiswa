<template>
  <v-container>
    <v-skeleton-loader
      type="table"
      :loading="isTableLoading"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers.beasiswa"
        :items="beasiswa"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-icon
            small
            @click="info(item)"
            title="Lihat detail"
          >
            mdi-information-outline
          </v-icon>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>

    <!-- Dialog Delete -->
    <div class="text-center">
      <v-dialog v-model="sheet" width="800">
        <v-card>
          <v-card-title class="headline white--text" primary-title>
            <i class="mdi mdi-account-check mr-2"></i> Penerima Beasiswa
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            <v-card-text>
                <h5>Beasiswa {{selectedBeasiswa.nama}}</h5>
                <p>Kuota ({{pemohon.length}}/{{selectedBeasiswa.quota}})</p>
            </v-card-text>
            <v-data-table
                :headers="headers.pemohon"
                :items="pemohon"
                :items-per-page="10"
                style="background-color: #2e7d323b"
                class="elevation-10 mb-10"
            >
                <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    @click="info(item)"
                    title="Lihat detail"
                >
                    mdi-information-outline
                </v-icon>
                </template>
                <template v-slot:no-data>
                no data
                </template>
            </v-data-table>
          </v-card-text>
        </v-card>
      </v-dialog>
    </div>
    <!-- Akhir -->
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaSelesai();
    this.getInstansi();
  },
  methods: {
    ...mapMutations(["toggleOpenBeasiswa"]),
    ...mapActions(["getBeasiswaSelesai", "getInstansi", "storeBeasiswa"]),
    compareType(a, b) {
      a == b ? true : false;
    },
    info(item){
        this.selectedBeasiswa = item;
        this.sheet = true;
        this.pemohon = item.selection.filter( function (value, index) {
            if (value.is_selection_passed){
                return value;
            }
        });
    }
  },
  computed: {
    ...mapState(["beasiswa", "isOpenBeasiswa", "instansi","isTableLoading","isLoading"]),
    toggleBeasiswa: {
      get: function() {
        return this.isOpenBeasiswa;
      },
      set: function(data) {
        this.toggleOpenBeasiswa(data);
      }
    }
  },
  data() {
    return {
      btnLoading:false,
      selectedBeasiswa: "",
      pemohon: [],
      sheet: false,
      headers: {
          beasiswa: [
                {
                    text: "Beasiswa",
                    align: "start",
                    sortable: false,
                    value: "nama"
                    },
                    { text: "Deskripsi", value: "deskripsi" },
                    { text: "Actions", value: "actions", sortable: false
                }
          ],
          pemohon: [
                {
                  text: "Nama",
                  align: "start",
                  sortable: false,
                  value: "mahasiswa.nama"
                },
                {
                    text: "NIM", value: "mhs_id"
                }
          ]
      }
    };
  }
};
</script>

<style scoped>
.fab {
  color: #2e7d323b;
  position: fixed;
  bottom: 20px;
  right: 20px;
}
.floating {
  width: 100%;
  position: fixed;
  bottom: 20px;
  z-index: 10;
}
</style>
