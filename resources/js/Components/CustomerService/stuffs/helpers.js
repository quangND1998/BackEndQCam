import moment from 'moment';

export const getCycleYear = (lifeTime, timeApprove) => {
  if (lifeTime <= 1) return '';

  const approveDate = moment(timeApprove, 'YYYY-MM-DD HH:mm:ss');
  const dateMilestones = Array(lifeTime).fill(0).map((_, index) => {
    return moment(approveDate).add((index + 1) * 12, 'months');
  });

  return `(${dateMilestones.findIndex(dateMilestone => dateMilestone.diff(new Date(), 'seconds') > 0) + 1})`;
}
