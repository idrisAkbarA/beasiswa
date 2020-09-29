<template>
  <v-card
    color="#E8F5E9"
    light
    elevation="20"
    width="70%"
    class="fill-height mx-auto"
    style="overflow-y: auto"
  >
    <v-card-text>
      <v-subheader>Permohonan Beasiswa</v-subheader>
      <v-row class="pl-8 pr-8">
        <v-text-field
          prepend-inner-icon="mdi-magnify"
          clearable
          label="Pencarian"
        ></v-text-field>
      </v-row>
      <v-row>
          <v-card-text>
            <p v-if="!beasiswa" class="text-center">Tidak ada peserta wawancara</p>
            <v-expansion-panels
            hover
            inset
            >
                <v-expansion-panel
                    v-for="(item,i) in beasiswa"
                    :key="i"
                >
                    <v-expansion-panel-header>
                    <v-row
                        no-gutters
                        justify="space-between"
                    >
                        <v-col cols="4"><strong>{{item.nama}}</strong></v-col>

                    </v-row>

                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <span class="text-muted">{{item.deskripsi}}</span>
                        <v-row>
                            <v-divider></v-divider>
                        </v-row>
                        <span>
                            Kuota penerima beasiswa : {{item.lulus.length}} / {{item.quota}} Orang
                        </span>
                        <v-badge
                        inline
                        content=" Terpenuhi"
                        v-if="item.lulus.length == item.quota"
                        ></v-badge>
                        <div class="col-12">
                            <div class="row">
                                <v-card class="col-6 bg-dark" elevation="0">
                                    <div class="overline mb-4 text-white text-center">
                                    Permohonan Masuk
                                    </div>
                                    <p v-if="item.selection.length < 1" class="text-white text-center">Tidak ada data</p>
                                    <v-btn v-for="(pemohon, i) in item.selection" :key="i"
                                        class="btn btn-light mb-1"
                                        block
                                        @click="lulus(pemohon)"
                                    >
                                        <span class="my-2">{{pemohon.mahasiswa.nama}}</span>
                                    </v-btn>
                                </v-card>
                                <v-card class="col-6 bg-dark" elevation="0">
                                    <div class="overline mb-4 text-white text-center">
                                    Lulus
                                    </div>
                                    <p v-if="item.lulus.length < 1" class="text-white text-center">Tidak ada data</p>
                                    <v-btn v-for="(pemohon, i) in item.lulus" :key="i"
                                        class="btn btn-light mb-1"
                                        block
                                        @click="lulus(pemohon)"
                                    >
                                        <span class="my-2">{{pemohon.mahasiswa.nama}}</span>
                                    </v-btn>
                                </v-card>
                            </div>
                        </div>
                        <v-row justify="end">
                            <v-btn
                            dark
                            color="#2E7D32"

                            @click="tutup(item)"
                            >Tutup Penerimaan beasiswa</v-btn>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
          </v-card-text>
      </v-row>
    </v-card-text>
    <!-- Dialog Delete -->
    <div class="text-center">
      <v-dialog v-model="dialogDelete" width="400">
        <v-card>
          <v-card-title class="headline white--text" primary-title>
            <v-icon color="white" class="mr-2">delete</v-icon>Hapus Mahasiswa
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            Apakah anda yakin akan menghapus Mahasiswa
            <span class="font-weight-bold"></span>?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn @click="dialogDelete = false" color="white" text>batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="red" dark @click="dialogDelete = false,deleteConfirmed()">
              <v-icon>delete</v-icon>Hapus
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- Snackbar -->
    <v-snackbar
      v-model="snackbar.show"
      :timeout="2000"
    >
      {{ snackbar.message }}

      <template v-slot:action="{ attrs }">
        <v-btn
          :color="snackbar.color"
          text
          v-bind="attrs"
          @click="snackbar.show = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-card>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswa();
  },
  methods: {
    ...mapActions(["getBeasiswa", "deleteBeasiswa"]),
    lulus(item) {
        this.btnLoading = true;
        axios
        .put(`${this.url}/api/pemohon/set-selection`, {
            bool: item.is_selection_passed == 1 ? 0 : 1,
            id: item.id
        })
        .then(response => {
            if (!response.data.status){
                this.snackbar = {
                    show: true,
                    color: "red",
                    message: "Kuota beasiswa sudah penuh!",
                }
            }else {
                this.getBeasiswa();
            }
            this.btnLoading = false;
        });
    },
    tutup(item) {
        this.btnLoading = true;
        this.deleteBeasiswa(item.id)
          .then(response => {
            this.btnLoading = false;
            this.snackbar = {
                show: true,
                color: "blue",
                message: "Beasiswa telah ditutup!",
            }
            getBeasiswa();
          })
          .catch(error => {
            this.btnLoading = false;
          });
    },
    link(url) {
      var a = this.url + "/" + url;
      var link = a.replace(" ", "%20");
      console.log(link);
      location = link;
    }
  },
  computed: {
    ...mapState(["beasiswa","url"])
  },
  data() {
    return {
      btnLoading:false,
      snackbar: {
          show: false
      },
      headers: [
        {
          text: "Nama Instansi",
          align: "start",
          sortable: false,
          value: "name"
        },
        { text: "Actions", value: "actions", sortable: false }
      ]
    };
  }
};
</script>

<style scoped>
.area {
  width: 70%;
  margin: auto;
  position: absolute;
  height: 100%;
  background: white;
}
</style>
