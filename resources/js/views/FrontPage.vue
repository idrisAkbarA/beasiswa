<template>
  <v-app>
    <!-- Sizes your content based upon application components -->
    <v-main class="bg-pattern">
      <v-sheet class="gridy fill-height">
        <div class="informasi hidden-xs-only bg-left elevation-10">
          <v-avatar
            class="mt-10 ml-10"
            :tile="true"
            size="100"
          >
            <img
              :src="'/images/LogoUIN.png'"
              alt="logo"
            >
          </v-avatar>
          <div>

          </div>
          <h4 class="mt-10 ml-10 mr-10 font-weight-light">Aplikasi
            Pendaftaran Beasiswa
            Uin Suska Riau</h4>
          <v-divider></v-divider>
          <h5 class="mt-10 ml-10 mr-10">Beasiswa tersedia: <span>
              <v-chip
                class="elevation-10"
                color="green"
                small
              >{{beasiswa.length}}</v-chip>
            </span></h5>
        </div>
        <div class="bg-pattern ">
          <v-app-bar
            class="hidden-sm-and-up"
            flat
            color="transparent"
            hide-on-scroll
          >
            <v-avatar :tile="true">
              <img
                :src="'/images/LogoUIN.png'"
                alt="logo"
              >
            </v-avatar>
            <div style="width:100%; -webkit-app-region: drag;">
              <v-toolbar-title>
                <span v-if="windowWidth >= 600" class="font-weight-bold ml-4">
                  App
                  Pendaftaran Beasiswa
                  UIN Suska Riau
                </span>
                <span v-if="windowWidth <= 600" class="font-weight-bold ml-4">
                  App
                  Pendaftaran Beasiswa
                </span>

              </v-toolbar-title>
            </div>
          </v-app-bar>
          <h4
            style="z-index:3; position:fixed;"
            class="font-weight-light ma-7"
          >Beasiswa Tersedia</h4>
          <v-btn
            @click="dialog=true"
            text
            color="green"
            style="z-index:3; position:fixed; right:0;"
            class="ma-7"
          >
            <v-icon>mdi-login-variant</v-icon> Login
          </v-btn>

          <vue-scroll :ops="ops">
            <v-sheet
              color="transparent"
              class="d-flex align-center align-self-center list-beasiswa "
            >
              <p class="text-center" style="width:40vw" v-if="beasiswa.length<1">Maaf belum ada beasiswa tersedia</p>
              <v-hover
                v-for="(item,index) in beasiswa"
                :key="index"
                v-slot:default="{ hover }"
              >
                <v-card
                  :elevation="hover ? 24 : 8"
                  @click="goto(item)"
                  ripple
                  class="ma-6"
                  :height="300"
                  :width="200"
                >
                  <v-img
                    :height="300"
                    :width="200"
                    gradient="to top right, rgba(58, 231, 87, 0.33), rgba(25,32,72,.7)"
                    :src="'https://picsum.photos/200/300?random='+index"
                  >

                    <template v-slot:placeholder>
                      <v-skeleton-loader
                        ref="skeleton"
                        :loading="loading"
                        type="image"
                        class="ma-auto "
                      >

                      </v-skeleton-loader>
                    </template>
                    <v-card-title><span>{{index+1}}</span>
                      <v-spacer></v-spacer><span class="caption">Tersedia</span>
                    </v-card-title>
                    <v-card-text style="height:100%">
                      <h2>{{item.nama.length>60 ? item.nama.substring(0,60) + " ..." : item.nama}}</h2>
                    </v-card-text>
                  </v-img>
                  <p class="caption pa-1">

                    <br v-if="item.total_sks">
                    <span v-if="item.total_sks">{{"Minimum "+ item.total_sks+" SKS"}}</span>
                    <br v-if="item.semester">
                    <span v-if="item.semester">{{"Semester "+ item.semester}}</span>
                    <br v-if="item.is_first">
                    <span v-if="item.is_first">Belum pernah mengikuti beasiswa</span>
                    <br v-if="item.ukt">
                    <span v-if="item.ukt"> Golongan UKT {{item.ukt }} kebawah</span>
                  </p>
                </v-card>

              </v-hover>

            </v-sheet>
          </vue-scroll>
          <v-bottom-sheet
            scrollable
            inset
            overlay-color="green"
            v-model="sheet"
          >
            <v-card>
              <v-card-title> <span>Rincian Beasiswa</span>
                <v-spacer></v-spacer>
                <v-btn
                  icon
                  @click="sheet=false"
                >
                  <v-icon>close</v-icon>
                </v-btn>
              </v-card-title>
              <v-card-subtitle>{{beasiswaSingle.nama}}</v-card-subtitle>
              <v-card-text>
                <vue-scroll :ops="opsSheet">
                  <v-container>
                    {{beasiswaSingle.deskripsi}}
                    <br><br>
                    <br v-if="beasiswaSingle.total_sks">
                    <span v-if="beasiswaSingle.total_sks">{{"Minimum "+ beasiswaSingle.total_sks+" SKS"}}</span>
                    <br v-if="beasiswaSingle.semester">
                    <span v-if="beasiswaSingle.semester">{{"Semester "+ beasiswaSingle.semester}}</span>
                    <br v-if="beasiswaSingle.is_first">
                    <span v-if="beasiswaSingle.is_first">Belum pernah mengikuti beasiswa</span>
                    <br v-if="beasiswaSingle.ukt">
                    <span v-if="beasiswaSingle.ukt">Golongan UKT {{beasiswaSingle.ukt }} kebawah</span>
                    <br><br>
                    Batas upload berkas <span v-if="beasiswaSingle.awal_berkas"><strong>{{parseDate(beasiswaSingle.awal_berkas)}}</strong> sampai </span> <strong> {{parseDate(beasiswaSingle.akhir_berkas)}}</strong>
                    <br>
                    <span v-if="beasiswaSingle.is_interview">Waktu wawancara <span v-if="beasiswaSingle.awal_interview"><strong>{{parseDate(beasiswaSingle.awal_interview)}} </strong></span> <span v-if="beasiswaSingle.akhir_interview"> sampai <strong>{{parseDate(beasiswaSingle.akhir_interview)}}</strong></span>  </span>
                    <br>
                    <span v-if="beasiswaSingle.is_survey">Waktu survey <span v-if="beasiswaSingle.awal_survey"><strong>{{parseDate(beasiswaSingle.awal_survey)}}</strong> </span> <span v-if="beasiswaSingle.akhir_survey">sampai <strong>{{parseDate(beasiswaSingle.akhir_survey)}} </strong>  </span> </span>
                  </v-container>

                  <v-divider></v-divider>
                  <v-container
                    fluid
                    v-if="beasiswaSingle.fields"
                  >
                    <v-row>
                      <v-col>
                        <!-- <span>Preview pengisian berkas. <router-link :to="{ path: '/login'}">Login</router-link> untuk mengisi</span> -->
                        <span>Preview pengisian berkas. <span>
                            <v-chip
                              color="green"
                              small
                              label
                              outlined=""
                              @click="dialog=true"
                            >login</v-chip>
                          </span> untuk mengisi</span>

                      </v-col>

                    </v-row>

                    <v-row
                      v-for="(field,index) in JSON.parse(beasiswaSingle.fields)"
                      :key="index"
                      no-gutters
                      dense
                    >
                      <v-col style="padding-bottom:0 !important;">
                        <p>{{field.pertanyaan}}</p>
                        <v-container v-if="field.type == 'Checkboxes'">
                          <v-row
                            align="center"
                            v-for="(item,index) in field.checkboxes.items"
                            :key="index"
                            :value="item.label"
                          >
                            <v-checkbox
                              disabled
                              color="white"
                              hide-details
                              class="shrink mr-2 mt-0"
                            ></v-checkbox>
                            <span>
                              {{item.label}}
                            </span>
                            <!-- <v-text-field
                              v-model="item.label"
                              dense
                              color="white"
                              filled
                              label="Label"
                            ></v-text-field> -->
                          </v-row>

                        </v-container>
                        <v-radio-group
                          disabled
                          v-if="field.type == 'Pilihan'"
                          column
                          :mandatory="field.pilihan.required"
                          v-model="field.value"
                        >
                          <v-radio
                            v-for="(item,index) in field.pilihan.items"
                            :key="index"
                            :value="item.label"
                            color="white"
                            :label="item.label"
                          >
                          </v-radio>
                        </v-radio-group>
                        <v-text-field
                          disabled
                          prepend-icon="mdi-text-short"
                          v-if="field.type == 'Jawaban Pendek'"
                          dense
                          color="white"
                          v-model="field.value"
                          placeholder="Jawaban Anda"
                        ></v-text-field>
                        <v-text-field
                          disabled
                          prepend-icon="mdi-numeric"
                          v-if="field.type == 'Jawaban Angka'"
                          dense
                          v-model="field.value"
                          color="white"
                          type="number"
                          placeholder="Jawaban Anda"
                        ></v-text-field>
                        <v-menu
                          v-if="field.type == 'Tanggal'"
                          v-model="field.date"
                          :close-on-content-click="false"
                          :nudge-right="40"
                          transition="scale-transition"
                          offset-y
                          min-width="290px"
                        >
                          <template v-slot:activator="{ on, attrs }">
                            <v-text-field
                              disabled
                              v-model="field.value"
                              label="Tanggal"
                              prepend-icon="mdi-calendar"
                              readonly
                              v-bind="attrs"
                              color="white"
                              v-on="on"
                            ></v-text-field>
                          </template>
                          <v-date-picker
                            v-model="field.value"
                            @input="menu2 = false"
                          ></v-date-picker>
                        </v-menu>
                        <v-file-input
                          disabled
                          v-if="field.type == 'Upload File'"
                          dense
                          v-model="field.value"
                          color="white"
                          placeholder="Upload File"
                        ></v-file-input>
                        <v-textarea
                          disabled
                          v-model="field.value"
                          prepend-icon="mdi-view-headline"
                          v-if="field.type == 'Paragraf'"
                          color="white"
                          rows="1"
                          dense
                          label="Paragraf"
                        ></v-textarea>
                      </v-col>
                    </v-row>
                  </v-container>
                </vue-scroll>
              </v-card-text>
            </v-card>
          </v-bottom-sheet>
        </div>
      </v-sheet>
    </v-main>
    <v-dialog
      v-model="dialog"
      :width="width()"
      overlay-color="green"
    >

      <LoginComponent></LoginComponent>

    </v-dialog>
  </v-app>
</template>

<script>
import LoginComponent from "./LoginComponent";
import { mapState, mapActions } from "vuex";
export default {
  created() {
    this.getBeasiswaNoAuth();
  },
  methods: {
    ...mapActions(["getBeasiswaNoAuth"]),
    isNoRule(item) {
      if (item.semester && item.total_sks && item.ukt && is_first) {
        return false;
      } else {
        return true;
      }
    },
    parseDate(date) {
      return this.$moment(date, "YYYY-MM-DD").format("Do MMMM YYYY");
    },

    goto(item) {
      this.sheet = true;
      this.beasiswaSingle = item;
      console.log(this.beasiswaSingle);
      //   console.log(this.item.awal_berkas)
      console.log(
        this.$moment(item.akhir_berkas, "YYYY-MM-DD").format("Do MMMM YYYY")
      );
    },
    width() {
      // console.log(this.windowWidth)
      if (this.windowWidth <= 600) {
        return "70%";
      } else if (this.windowWidth <= 960) {
        return "30%";
      } else {
        return "20%";
      }
    }
  },
  computed: {
    ...mapState(["beasiswa", "url"]),
    count() {
      return this.beasiswa.length;
    }
  },
  data() {
    return {
      dialog: false,
      sheet: false,
      beasiswaSingle: {},
      loading: false,
      ops: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: true
        }
      },
      opsSheet: {
        scrollPanel: {
          easing: "easeInQuad",
          speed: 800
        },
        vuescroll: {
          wheelScrollDuration: 0,
          wheelDirectionReverse: false
        }
      }
    };
  },
  components: {
    LoginComponent
  }
};
</script>

<style scoped>
.list-beasiswa {
  position: absolute;
  height: 100%;
}

.gridy {
  position: relative;
  display: grid;
  grid-template-columns: 2fr 5fr;
}
.bg-pattern {
  margin: 0;
  padding: 0;
  background: url("/pattern.svg") repeat;
  background-size: 400px;
}
.bg-left {
  background: url("/images/drawer-bg.jpg");
  background: linear-gradient(
      180deg,
      rgba(0, 36, 9, 0.582992938386292) 0%,
      rgba(114, 228, 251, 0.4681470000109419) 100%
    ),
    url("/images/drawer-bg.jpg");
  background-size: cover;
}
::-webkit-scrollbar {
  width: 0px;
}
a {
  text-decoration: none !important;
}

@media (max-width: 600px) {
  .gridy {
    position: relative;
    display: grid;
    grid-template-columns: 1fr;
  }
}
</style>