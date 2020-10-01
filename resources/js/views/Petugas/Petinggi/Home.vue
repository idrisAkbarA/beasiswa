<template>
  <v-card
    color="#E8F5E9"
    light
    elevation="20"
    :width="width()"
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
          <p
            v-if="!beasiswa"
            class="text-center"
          >Tidak ada peserta wawancara</p>
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
                  Kuota penerima beasiswa : {{Object.keys(item.lulus).length}} / {{item.quota}} Orang
                </span>
                <v-badge
                  inline
                  content=" Terpenuhi"
                  v-if="Object.keys(item.lulus).length == item.quota"
                ></v-badge>
                <div class="col-12">
                  <div class="row">
                    <v-card
                      class="col-6 bg-dark"
                      elevation="0"
                    >
                      <div class="overline mb-4 text-white text-center">
                        Permohonan Masuk
                      </div>
                      <p
                        v-if="item.selection.length < 1"
                        class="text-white text-center"
                      >Tidak ada data</p>
                      <v-btn
                        v-for="(pemohon, i) in item.selection"
                        :key="i"
                        class="btn btn-light mb-1"
                        block
                        @click="dialogDetail = true, selectedMahasiswa = pemohon"
                      >
                        <span class="my-2">{{pemohon.mahasiswa.nama}}</span>
                      </v-btn>
                    </v-card>
                    <v-card
                      class="col-6 bg-dark"
                      elevation="0"
                    >
                      <div class="overline mb-4 text-white text-center">
                        Lulus
                      </div>
                      <p
                        v-if="item.lulus.length < 1"
                        class="text-white text-center"
                      >Tidak ada data</p>
                      <v-btn
                        v-for="(pemohon, i) in item.lulus"
                        :key="i"
                        class="btn btn-light mb-1"
                        block
                        @click="selectedMahasiswa = pemohon, lulus()"
                      >
                        <span class="my-2">{{pemohon.mahasiswa.nama}}</span>
                      </v-btn>
                    </v-card>
                  </div>
                </div>
                <v-row justify="end">
                  <v-btn
                    :disabled="btnLoading"
                    :dark="!btnLoading"
                    color="#2E7D32"
                    class="mr-3"
                    @click="tutup(item)"
                  >Tutup Penerimaan beasiswa</v-btn>

                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-row>
    </v-card-text>
    <!-- Dialog Detail -->
    <div
      class="text-center"
      v-if="dialogDetail"
    >
      <v-dialog
        v-model="dialogDetail"
        width="600"
      >
        <v-card>
          <v-card-title
            class="headline white--text"
            primary-title
          >
            <i class="mdi mdi-account mr-2"></i> {{selectedMahasiswa.mahasiswa.nama}}
          </v-card-title>

          <v-card-text class="mt-2 white--text">
            detail
                          <v-row
                no-gutters=""
                class="ma-5"
                v-for="(field,index) in JSON.parse(selectedMahasiswa.form)"
                :key="index"
              >

                <v-col style="padding-bottom:0 !important;">
                  <p>{{field.pertanyaan}}</p>
                  <p v-if="field.type == 'Pilihan'"><span>
                      <v-icon>mdi-text-short</v-icon>{{field.value}}
                    </span></p>
                  <p v-if="field.type == 'Jawaban Pendek'"><span>
                      <v-icon>mdi-text-short</v-icon>{{field.value}}
                    </span></p>
                  <p v-if="field.type == 'Jawaban Angka'"><span>
                      <v-icon>mdi-text-short</v-icon>{{field.value}}
                    </span></p>
                  <p v-if="field.type == 'Tanggal'"><span>
                      <v-icon>mdi-text-short</v-icon>{{field.value}}
                    </span></p>
                  <v-btn
                    v-if="field.type == 'Upload File'"
                    small
                    @click="link(field.value)"
                  >lihat file</v-btn>
                  <p v-if="field.type == 'Paragraf'"><span>
                      <v-icon>mdi-text-short</v-icon>{{field.value}}
                    </span></p>
                </v-col>
                <v-col cols="12">
                  <v-divider></v-divider>
                </v-col>
                <v-col cols="12">

                </v-col>
              </v-row>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn
              @click="dialogDetail = false, selectedMahasiswa = {}"
              color="white"
              text
            >batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn
              color="#2E7D32"
              dark
              @click="lulus()"
            >
              <v-icon>check</v-icon>Lulus
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <!-- Dialog Delete -->
    <div class="text-center" v-if="dialogDelete">
      <v-dialog v-model="dialogDelete" width="400" overlay-color="#2E7D32">
        <v-card>
          <v-card-title class="headline white--text" primary-title>
            <i class="mdi mdi-checkbox-marked-circle-outline mr-2"></i> Tutup Penerimaan Beasiswa
          </v-card-title>

          <v-card-text class="white--text text-center mt-2">
            <strong class="d-block">{{this.selectedBeasiswa.nama}}</strong>
            <small>
                  Kuota penerima beasiswa : {{Object.keys(this.selectedBeasiswa.lulus).length}} / {{this.selectedBeasiswa.quota}} Orang
            <v-badge
                inline
                content=" Terpenuhi"
                v-if="Object.keys(this.selectedBeasiswa.lulus).length == this.selectedBeasiswa.quota"
            ></v-badge>
            <v-badge
                inline
                color="red"
                content=" Tdk Terpenuhi"
                v-if="Object.keys(this.selectedBeasiswa.lulus).length < this.selectedBeasiswa.quota"
            ></v-badge>
            </small>
          </v-card-text>
          <v-card-text class="white--text text-center pb-0">
            Apakah anda yakin akan menutup beasiswa ini ?
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-btn @click="dialogDelete = false" color="white" text>batal</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="#2E7D32" dark @click="dialogDelete = false, deleteBeasiswa()">
              Selesai
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
    this.getBeasiswaWithPermohonan();
  },
  methods: {
    width(){
      // console.log(this.windowWidth)
      if(this.windowWidth<=600){ return "100%"}
      else if(this.windowWidth<=960){return "85%"}
      else{
        return "70%"
      };
    },
    ...mapActions(["getBeasiswaWithPermohonan", "deleteBeasiswa"]),
    lulus() {
        console.log(this.selectedMahasiswa);
        this.btnLoading = true;
        axios
        .put(`${this.url}/api/pemohon/set-selection`, {
            bool: this.selectedMahasiswa.is_selection_passed == 1 ? 0 : 1,
            id: this.selectedMahasiswa.id
        })
        .then(response => {
            if (!response.data.status){
                this.snackbar = {
                    show: true,
                    color: "red",
                    message: "Kuota beasiswa sudah penuh!",
                }
            }else {
                this.getBeasiswaWithPermohonan();
            }
            this.btnLoading = false;
            this.dialogDetail = false;
            this.selectedMahasiswa = {};
        });
    },
    tutup(item) {
        this.dialogDelete = true;
        this.selectedBeasiswa = item;
    },
    deleteBeasiswa() {
        this.btnLoading = true;
        this.deleteBeasiswa(item.id)
          .then(response => {
            this.btnLoading = false;
            this.snackbar = {
                show: true,
                color: "blue",
                message: "Beasiswa telah ditutup!",
            }
            getBeasiswaWithPermohonan();
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
      selectedBeasiswa: {},
      selectedMahasiswa: {},
      dialogDelete: false,
      dialogDetail: false,
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
