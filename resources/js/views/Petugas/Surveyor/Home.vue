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
          <p v-if="cekSurvey ==[]">Tidak ada peserta survey</p>
        <v-expansion-panels
          hover
          inset
        >
          <v-expansion-panel
            v-for="(item,i) in cekSurvey"
            :key="i"
          >
            <v-expansion-panel-header>
              <v-row
                no-gutters
                justify="space-between"
              >
                <v-col cols="4">{{item.nama}}</v-col>

                <v-col cols="4">{{item.nama_beasiswa}}</v-col>
              </v-row>

            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <p>Rincian</p>
              <table>
                <tr>
                  <td>Nama</td>
                  <td>:</td>
                  <td>{{item.nama}}</td>
                </tr>
                <tr>
                  <td>NIM</td>
                  <td>:</td>
                  <td>{{item.mhs_id}}</td>
                </tr>
                <tr>
                  <td>Beasiswa</td>
                  <td>:</td>
                  <td>{{item.nama_beasiswa}}</td>
                </tr>
              </table>
              <v-row>
                <v-divider></v-divider>
              </v-row>
              <p>Berkas</p>
              <v-row
                no-gutters=""
                class="ma-5"
                v-for="(field,index) in item.form"
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
              <v-row justify="end">

                <v-btn
                  @click="tidakLulusButton(item)"
                  text
                >Tidak Lulus</v-btn>
                <v-btn
                  dark
                  color="#2E7D32"

                  @click="lulusButton(item)"
                >Lulus</v-btn>

              </v-row>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>

      </v-row>

    </v-card-text>
    <v-dialog
      width="460"
      overlay-color="#69F0AE"
      v-model="dialog"
    >
      <v-card>
        <v-card-title class="mt-2">
          <span v-html="msg"></span>
          <p style="font-weight:bold">
            <!-- {{itemtoDelete.nama}}? -->
          </p>
        </v-card-title>
        <v-card-actions>

          <v-btn
            text
            @click="dialog = false"
          >
            Batal
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            dark
            class="green"
                :loading="btnLoading"
            @click="setSurvey"
          >
            <v-icon left>mdi-check</v-icon>
            iya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswaWithPermohonan();

  },
  methods: {
    ...mapActions(["getBeasiswaWithPermohonan"]),
    lulusButton(item) {
      this.dialog = true;
      this.id = item.id;
      this.bool = 1;
      this.msg =
        "Apakah anda yakin bahwa pemohon <strong>lulus</strong> tahap survey?";
    },
    tidakLulusButton(item) {
      this.dialog = true;
      this.id = item.id;
      this.bool = 0;
      this.msg =
        "Apakah anda yakin bahwa pemohon <strong>tidak lulus</strong> tahap survey?";
    },
    link(url) {
      var a = this.url + "/" + url;
      var link = a.replace(" ", "%20");
      console.log(link);
      location = link;
    },
    setSurvey() {
        this.btnLoading = true;
      axios
        .put(`${this.url}/api/pemohon/set-survey`, {
          bool: this.bool,
          id: this.id
        })
        .then(response => {
          this.getBeasiswaWithPermohonan();
          console.log(response.data);
          this.dialog = false;
          this.btnLoading = false;
        });
    }
  },
  computed: {
    ...mapState(["beasiswa","url"])
  },
  data() {
    return {
        btnLoading:false,
      dialog: false,
      msg: "",
      bool: 0,
      id: 0,
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
a {
  text-decoration: none !important;
}
.area {
  width: 70%;
  margin: auto;
  position: absolute;
  height: 100%;
  background: white;
}
</style>

