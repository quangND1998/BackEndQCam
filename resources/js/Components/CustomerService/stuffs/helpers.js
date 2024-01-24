import moment from 'moment';

export const getCycleYear = (lifeTime, timeApprove) => {
  if (lifeTime <= 1) return '';

  const approveDate = moment(timeApprove, 'YYYY-MM-DD HH:mm:ss');
  const dateMilestones = Array(lifeTime).fill(0).map((_, index) => {
    return moment(approveDate).add((index + 1) * 12, 'months');
  });

  return `(${dateMilestones.findIndex(dateMilestone => dateMilestone.diff(new Date(), 'seconds') > 0) + 1})`;
}

export const generatePageNumbers(currentPage, totalPages) {
  let startPage, endPage;
  if (totalPages <= 5) {
    startPage = 1;
    endPage = totalPages;
  } else {
    if (currentPage <= 3) {
      startPage = 1;
      endPage = 5;
    } else if (currentPage + 2 >= totalPages) {
      startPage = totalPages - 4;
      endPage = totalPages;
    } else {
      startPage = currentPage - 2;
      endPage = currentPage + 2;
    }
  }

  let pageNumbers = Array.from({length: (endPage - startPage + 1)}, (_, i) => startPage + i);

  return pageNumbers;
}
