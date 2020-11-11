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
      <v-subheader>
        <strong class="text-dark">History interview</strong>
      </v-subheader>
      <v-row class="pl-8 pr-8">
        <v-text-field prepend-inner-icon="mdi-magnify" clearable label="Pencarian"></v-text-field>
      </v-row>
      <v-row>
        <v-card-text>
          <v-row v-if="loading" justify="center" align-content="center" class="text-center">
            <v-col cols="12">
              <v-progress-circular class="mx-auto" color="green" indeterminate></v-progress-circular>
            </v-col>
            <v-col cols="12">Memuat data</v-col>
          </v-row>
          <p v-if="!beasiswa.length && !loading" class="text-center">Tidak ada berkas</p>
          <v-expansion-panels hover inset v-if="!loading">
            <v-expansion-panel v-for="(item,i) in beasiswa" :key="i">
              <v-expansion-panel-header>
                <v-row no-gutters align="center" justify="space-between">
                  <v-col cols="6">
                    <strong>{{item.nama}}</strong>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.permohonan).length < 1"
                  >
                    <span class="caption text-muted">Belum ada permohonan masuk</span>
                  </v-col>
                  <v-col
                    class="text-right mr-3"
                    cols="4"
                    v-if="Object.keys(item.permohonan).length > 0"
                  >
                    <v-chip
                      class="text-center"
                      small
                      label
                      dark
                      color="green"
                    >{{Object.keys(item.permohonan).length}} Permohonan diinterview</v-chip>
                  </v-col>
                </v-row>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <span class="text-muted">{{item.deskripsi}}</span>
                <v-row>
                  <v-divider class="mb-0"></v-divider>
                </v-row>
                <v-list>
                  <v-text-field
                    prepend-inner-icon="mdi-magnify"
                    clearable
                    label="Pencarian"
                    v-model="searchQuery"
                    @focus="index = i"
                  ></v-text-field>
                  <v-subheader>Permohonan ({{!searchQuery ? Object.keys(item.permohonan).length : resultQuery.length}})</v-subheader>
                  <v-list-item-group class="bg-white" color="primary">
                    <template
                      v-for="(permohonan, index) in !searchQuery ? item.permohonan : resultQuery"
                    >
                      <v-list-item
                        :key="index"
                        @click="sheetDetail = true, selectedPermohonan = permohonan, parsedForm = JSON.parse(permohonan.form)"
                      >
                        <template>
                          <v-list-item-content>
                            <v-list-item-title v-text="permohonan.mahasiswa.nama"></v-list-item-title>
                            <v-list-item-subtitle
                              v-text="`${permohonan.mahasiswa.jurusan.nama} (${permohonan.mahasiswa.fakultas.nama})`"
                            ></v-list-item-subtitle>
                          </v-list-item-content>
                          <v-list-item-action>
                            <v-icon color="blue" v-if="permohonan.is_interview_passed">mdi-check</v-icon>
                            <v-icon color="red" v-else>mdi-close</v-icon>
                          </v-list-item-action>
                        </template>
                      </v-list-item>
                      <v-divider
                        v-if="index < Object.keys(item.permohonan).length - 1"
                        :key="'divider '+index"
                        class="my-0"
                      ></v-divider>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from "vuex";
export default {
  computed: {
    ...mapState(["url"]),
    resultQuery() {
      if (this.searchQuery) {
        if (typeof this.beasiswa[this.index].permohonan == "object") {
          this.beasiswa[this.index].permohonan = Object.values(
            this.beasiswa[this.index].permohonan
          );
        }
        return this.beasiswa[this.index].permohonan.filter(item => {
          return this.searchQuery
            .toLowerCase()
            .split(" ")
            .every(v => item.mahasiswa.nama.toLowerCase().includes(v));
        });
      } else {
        return this.beasiswa.permohonan;
      }
    }
  },
  created() {
    this.getHistory();
  },
  methods: {
    getHistory() {
      this.loading = true;
      axios
        .get(`${this.url}/api/permohonan/my-history`, {
          params: {
            key: "interview"
          }
        })
        .then(response => {
          this.beasiswa = response.data;
        })
        .catch(error => {
          console.error(error);
          this.snackbar = {
            show: true,
            color: "red",
            message: error
          };
        })
        .then(() => {
          this.loading = false;
        });
    }
  },
  data() {
    return {
      loading: false,
      searchQuery: "",
      beasiswa: []
    };
  }
};
</script>

<style>
</style>
