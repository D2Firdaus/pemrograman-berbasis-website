function checkGrade() {
  const nim = document.getElementById("nim-input").value;
  const nilai = parseInt(document.getElementById("grade-input").value);
  const result = document.getElementById("grade-result");

  if (nilai < 0 || nilai > 100 || isNaN(nilai)) {
    result.textContent = "Input salah";
    result.style.fontSize = "1rem";
    return;
  }

  result.style.fontSize = "";

  let grade = "";
  if (nilai >= 80) grade = "A";
  else if (nilai >= 70) grade = "B";
  else if (nilai >= 60) grade = "C";
  else if (nilai >= 50) grade = "D";
  else grade = "E";

  result.textContent = grade;
}
