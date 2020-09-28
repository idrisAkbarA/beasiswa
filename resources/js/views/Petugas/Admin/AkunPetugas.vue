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
  computed: {
    ...mapState(["nim", "url", "isTableLoading"])
  },
  data() {
    return {
      opensheet: false,
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

<style>
</style>