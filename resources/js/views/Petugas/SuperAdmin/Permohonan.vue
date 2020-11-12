<template>
  <v-container fluid>
    <v-skeleton-loader
      :loading="isTableLoading"
      type="table"
      transition="fade-transition"
    >

      <v-data-table
        :headers="headers"
        :items="cekBerkas"
        :items-per-page="10"
        style="background-color: #2e7d323b"
        class="elevation-10 mb-10"
      >
        <template v-slot:item.actions="{ item }">
          <v-btn
            small
            @click="detail(item)"
          >lihat rincian</v-btn>
        </template>
        <template v-slot:no-data>
          no data
        </template>
      </v-data-table>
    </v-skeleton-loader>
    <v-bottom-sheet
      scrollable
      width="60%"
      inset
      overlay-color="#69F0AE"
      v-model="openSheet"
    >
      <v-card>
        <v-card-title> <span>Cek Berkas</span>
          <v-spacer></v-spacer>
          <v-btn
            @click="tidakLulusButton"
            text
          >Tidak Lulus</v-btn>
          <v-btn
            color="#2E7D32"
            :loading="btnLoading"
            v-model="openSheet"
            @click="lulusButton"
          >Lulus</v-btn>
        </v-card-title>
        <v-card-text>
          <v-row class="ma-5">
            <v-col cols="12">
              <table style="width:100%">
                <tr>
                  <td style="width:20px">Nama</td>
                  <td>:</td>
                  <td>{{rincian.nama}}</td>
                </tr>
                <tr>
                  <td style="width:20px">NIM</td>
                  <td>:</td>
                  <td>{{rincian.mhs_id}}</td>
                </tr>
              </table>
            </v-col>
          </v-row>
          <v-row
            class="ma-5"
            v-for="(field,index) in rincian.form"
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
          </v-row>
        </v-card-text>
      </v-card>
    </v-bottom-sheet>
    <v-dialog
      width="400"
      overlay-color="#69F0AE"
      v-model="dialog"
    >
      <v-card>
        <v-card-title class="mt-2">
          {{msg}}
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
            @click="setBerkas"
          >
            <v-icon left>mdi-check</v-icon>
            iya
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getCekBerkas();
    console.log("ttt");
  },
  computed: {
    ...mapState(["cekBerkas", "beasiswaSingle", "nim", "isTableLoading"])
  },
  methods: {
    ...mapActions(["getCekBerkas"]),
    detail(item) {
      console.log(item);
      this.openSheet = true;
      this.rincian = item;
    },
    link(url) {
      var a = this.url + "/" + url;
      var link = a.replace(" ", "%20");
      console.log(link);
      location = link;
    },
    lulusButton() {
      this.dialog = true;
      this.bool = 1;
      this.msg = "Apakah anda yakin bahwa berkas ini lulus tahap cek berkas?";
    },
    tidakLulusButton() {
      this.dialog = true;
      this.bool = 0;
      this.msg = "Apakah anda yakin bahwa berkas ini didiskualifikasi?";
    },
    setBerkas() {
      axios
        .put(`/api/pemohon/set-berkas`, {
          bool: this.bool,
          id: this.rincian.id
        })
        .then(response => {
            this.getCekBerkas();
          console.log(response.data);
          this.dialog = false;
          this.openSheet=false
        });
    }
  },
  data() {
    return {
      bool: null,
      dialog: false,
      msg: "",
      rincian: {},
      btnLoading: false,
      openSheet: false,
      headers: [
        {
          text: "Beasiswa",
          align: "start",
          sortable: false,
          value: "nama_beasiswa"
        },
        { text: "Nama", value: "nama" },
        { text: "NIM", value: "mhs_id" },
        { text: "Actions", value: "actions", sortable: false }
      ]
    };
  }
};
</script>

<style scoped>
.delete-header {
  background: linear-gradient(-90deg, rgb(255, 70, 70), rgb(255, 255, 255));
  background-position: 50px 0px;
  background-repeat: no-repeat;
}
</style>
