import { html } from 'https://js.imlin.us/component'

export default function Card (props, fuel) {
  const { data, historicPrices } = props

  const startOfYear = data.startOfYear[fuel]
  const current = data.current[fuel]
  const percentage = data.percentage[fuel]

  return () => html`
    <div class="card">
      <div class="chart" data-fuel="${fuel}"></div>
      <p class="title">${fuel}</p>
      <p class="percentage">${percentage}%</p>
      <p class="heading">${startOfYear}kr &rightarrow; ${current}kr</p>
    </div>
  `
}
