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
                        <!-- <span class="text-muted">{{item.quota}}</span> -->
                        <span class="text-muted">{{item.deskripsi}}</span>
                        <v-row>
                            <v-divider></v-divider>
                        </v-row>
                        <span>Kuota penerima beasiswa : {{item.quota}} Orang</span>
                        <div class="col-12">
                            <div class="row">
                                <v-card class="col-6 bg-dark" elevation="0">
                                    <div class="overline mb-4 text-white text-center">
                                    Permohonan Masuk
                                    </div>
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
          </v-card-text>
      </v-row>

    </v-card-text>
  </v-card>
</template>

<script>
import { mapActions, mapMutations, mapState } from "vuex";
export default {
  created() {
    this.getBeasiswa();
  },
  methods: {
    ...mapActions(["getBeasiswa"]),
    lulus(item) {
        var data = [];
        data.id = item.id;
        data.bool = item.is_selection_passed == 1 ? 0 : 1;
        axios.put(this.url+"/pemohon/set-interview", data)
    },
    tidakLulusButton(item) {
      this.dialog = true;
      this.id = item.id;
      this.bool = 0;
      this.msg =
        "Apakah anda yakin bahwa pemohon <strong>tidak lulus</strong> tahap wawancara?";
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
.area {
  width: 70%;
  margin: auto;
  position: absolute;
  height: 100%;
  background: white;
}
</style>
