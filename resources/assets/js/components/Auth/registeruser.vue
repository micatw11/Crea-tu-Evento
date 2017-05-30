<template>
  <form-group :valid.sync="valid.all">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <tabs :active.sync="active">
        <tab header="User">
          <form-group :valid.sync="valid.user">
            <bs-input label="User Name" required></bs-input>
            <bs-input label="Email" type="email" required></bs-input>
          </form-group>
          <button type="button" class="btn btn-primary" :disabled="!valid.user" @click="active = 1">Continue</button>
        </tab>
        <tab header="Direction" :disabled="!valid.user">
          <form-group :valid.sync="valid.direction">
            <div class="form-group">
              <label class="control-label">Continent</label>
              <v-select required justified :options="['America','Europe']" clear-button></v-select>
            </div>
            <bs-input label="City" type="text" required></bs-input>
          </form-group>
          <button type="button" class="btn btn-primary" :disabled="!valid.all">Done!!</button>
        </tab>
      </tabs>
    </div>
  </form-group>
</template>